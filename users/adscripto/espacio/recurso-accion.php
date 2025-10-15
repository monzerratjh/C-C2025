<?php /*
// recurso-accion.php
// Controlador para CRUD de recursos (vinculados a un espacio).
include('../../../conexion.php');
$con = conectar_bd();
session_start();

header('Content-Type: application/json; charset=utf-8');

$accion = $_POST['accion'] ?? '';
$idRecurso = isset($_POST['id_recurso']) ? intval($_POST['id_recurso']) : null;
$idEspacio = isset($_POST['id_espacio']) ? intval($_POST['id_espacio']) : null;
$nombreRecurso = trim($_POST['nombre_recurso'] ?? '');
$tipoRecurso = trim($_POST['tipo_recurso'] ?? '');
$disponibilidadRecurso = trim($_POST['disponibilidad_recurso'] ?? '');
$estadoRecurso = $_POST['estado_recurso'] ?? '';
$historialRecurso = trim($_POST['historial_recurso'] ?? '');

// Validaciones servidor
function validarNombreRecurso($nombre) {
    return preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \-_,().]{2,150}$/u', $nombre);
}

function registrarAuditoriaRecurso($conexion, $usuarioId, $accion, $detalle) {
    $stmt = $conexion->prepare("INSERT INTO auditoria_acciones (id_usuario, accion, detalle_accion, fecha_hora) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $usuarioId, $accion, $detalle);
    $stmt->execute();
    $stmt->close();
}

// Obtener valores enum de estado_recurso
$enumEstado = $con->query("SHOW COLUMNS FROM recurso LIKE 'estado_recurso'");
$filaEnumEstado = $enumEstado->fetch_assoc();
preg_match_all("/'([^']+)'/", $filaEnumEstado['Type'], $coincidenciasEstado);
$valoresEstadoRecurso = $coincidenciasEstado[1];

try {
    if ($accion === 'insertar') {
        if (!$idEspacio) throw new Exception("ID de espacio inválido.");
        if (!validarNombreRecurso($nombreRecurso)) throw new Exception("Nombre de recurso inválido.");
        if ($tipoRecurso === '') throw new Exception("Tipo de recurso obligatorio.");
        if ($estadoRecurso === '' || !in_array($estadoRecurso, $valoresEstadoRecurso, true)) throw new Exception("Estado de recurso inválido.");

        $stmtInsert = $con->prepare("INSERT INTO recurso (disponibilidad_recurso, nombre_recurso, historial_recurso, tipo_recurso, estado_recurso, id_espacio) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("sssssi", $disponibilidadRecurso, $nombreRecurso, $historialRecurso, $tipoRecurso, $estadoRecurso, $idEspacio);
        if (!$stmtInsert->execute()) throw new Exception($stmtInsert->error);
        $stmtInsert->close();

        $usuarioIdSesion = $_SESSION['id_usuario'] ?? null;
        if ($usuarioIdSesion) registrarAuditoriaRecurso($con, $usuarioIdSesion, 'INSERTAR_RECURSO', "Se insertó recurso '{$nombreRecurso}' en espacio ID {$idEspacio}");

        echo json_encode(["type" => "success", "message" => "Recurso agregado correctamente."]);
        exit;
    }

    elseif ($accion === 'editar') {
        if (!$idRecurso) throw new Exception("ID de recurso inválido.");
        if (!validarNombreRecurso($nombreRecurso)) throw new Exception("Nombre de recurso inválido.");
        if ($tipoRecurso === '') throw new Exception("Tipo de recurso obligatorio.");
        if ($estadoRecurso === '' || !in_array($estadoRecurso, $valoresEstadoRecurso, true)) throw new Exception("Estado de recurso inválido.");

        $stmtActualizar = $con->prepare("UPDATE recurso SET disponibilidad_recurso = ?, nombre_recurso = ?, historial_recurso = ?, tipo_recurso = ?, estado_recurso = ?, id_espacio = ? WHERE id_recurso = ?");
        $stmtActualizar->bind_param("ssssiii", $disponibilidadRecurso, $nombreRecurso, $historialRecurso, $tipoRecurso, $estadoRecurso, $idEspacio, $idRecurso);
        if (!$stmtActualizar->execute()) throw new Exception($stmtActualizar->error);
        $stmtActualizar->close();

        $usuarioIdSesion = $_SESSION['id_usuario'] ?? null;
        if ($usuarioIdSesion) registrarAuditoriaRecurso($con, $usuarioIdSesion, 'EDITAR_RECURSO', "Se editó recurso ID {$idRecurso}: {$nombreRecurso}");

        echo json_encode(["type" => "success", "message" => "Recurso actualizado correctamente."]);
        exit;
    }

    elseif ($accion === 'eliminar') {
        if (!$idRecurso) throw new Exception("ID de recurso inválido.");

        // Validar si existe referencia en otra tabla si fuera necesario
        // Ejemplo: verificar si recurso está reservado (no implementado aquí por falta de FK explícita)
        $stmtEliminar = $con->prepare("DELETE FROM recurso WHERE id_recurso = ?");
        $stmtEliminar->bind_param("i", $idRecurso);
        if (!$stmtEliminar->execute()) throw new Exception($stmtEliminar->error);
        $stmtEliminar->close();

        $usuarioIdSesion = $_SESSION['id_usuario'] ?? null;
        if ($usuarioIdSesion) registrarAuditoriaRecurso($con, $usuarioIdSesion, 'ELIMINAR_RECURSO', "Se eliminó recurso ID {$idRecurso}");

        echo json_encode(["type" => "success", "message" => "Recurso eliminado correctamente."]);
        exit;
    }

    else {
        throw new Exception("Acción no reconocida.");
    }
} catch (Exception $excepcion) {
    echo json_encode(["type" => "error", "message" => $excepcion->getMessage()]);
    exit;
}
*/