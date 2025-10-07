<?php
// espacio-accion.php
// Controlador para insertar, editar y eliminar espacios.
include('../../../conexion.php');
$con = conectar_bd();
session_start();

header('Content-Type: application/json; charset=utf-8');

$accion = $_POST['accion'] ?? '';
$idEspacio = isset($_POST['id_espacio']) ? intval($_POST['id_espacio']) : null;
$nombreEspacio = trim($_POST['nombre_espacio'] ?? '');
$capacidadEspacio = isset($_POST['capacidad_espacio']) ? intval($_POST['capacidad_espacio']) : null;
$disponibilidadEspacio = $_POST['disponibilidad_espacio'] ?? '';
$historialEspacio = trim($_POST['historial_espacio'] ?? '');
$tipoEspacio = $_POST['tipo_espacio'] ?? '';

// Valores permitidos para el tipo de espacio
$tiposPermitidos = ['Salón', 'Aula', 'Laboratorio'];

// Función para obtener los valores del ENUM 'disponibilidad_espacio'
function obtenerValoresDisponibilidad($conexion) {
    $resultado = $conexion->query("SHOW COLUMNS FROM espacio LIKE 'disponibilidad_espacio'");
    $fila = $resultado->fetch_assoc();
    preg_match_all("/'([^']+)'/", $fila['Type'], $coincidencias);
    return $coincidencias[1];
}

// Validación de nombre (permitir letras, números y signos comunes)
function validarNombreEspacio($nombre) {
    return preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \-_,().]{2,120}$/u', $nombre);
}

// Función para registrar auditoría
function registrarAuditoria($conexion, $usuarioId, $accionRealizada, $detalleAccion) {
    $stmt = $conexion->prepare("INSERT INTO auditoria_acciones (id_usuario, accion, detalle_accion, fecha_hora) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $usuarioId, $accionRealizada, $detalleAccion);
    $stmt->execute();
    $stmt->close();
}

try {
    $valoresDisponibilidad = obtenerValoresDisponibilidad($con);

    // Validaciones comunes
    if ($nombreEspacio === '' || !validarNombreEspacio($nombreEspacio)) {
        throw new Exception("Nombre de espacio inválido (2-120 caracteres permitidos).");
    }
    if ($capacidadEspacio < 1 || $capacidadEspacio > 500) {
        throw new Exception("Capacidad inválida (1-500).");
    }
    if (!in_array($disponibilidadEspacio, $valoresDisponibilidad, true)) {
        throw new Exception("Disponibilidad inválida.");
    }
    if (!in_array($tipoEspacio, $tiposPermitidos, true)) {
        throw new Exception("Tipo de espacio inválido.");
    }

    if ($accion === 'insertar') {
        $stmtInsert = $con->prepare(
            "INSERT INTO espacio (nombre_espacio, capacidad_espacio, historial_espacio, disponibilidad_espacio, tipo) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmtInsert->bind_param("sisss", $nombreEspacio, $capacidadEspacio, $historialEspacio, $disponibilidadEspacio, $tipoEspacio);
        if (!$stmtInsert->execute()) {
            throw new Exception($stmtInsert->error);
        }
        $stmtInsert->close();

        $usuarioIdSesion = $_SESSION['id_usuario'] ?? null;
        if ($usuarioIdSesion) {
            registrarAuditoria($con, $usuarioIdSesion, 'INSERTAR_ESPACIO', "Se insertó espacio: {$nombreEspacio} ({$tipoEspacio})");
        }

        echo json_encode(["type" => "success", "message" => "Espacio agregado correctamente."]);
        exit;
    }

    elseif ($accion === 'editar') {
        if (!$idEspacio) {
            throw new Exception("ID de espacio inválido para editar.");
        }

        $stmtActualizar = $con->prepare(
            "UPDATE espacio 
             SET nombre_espacio = ?, capacidad_espacio = ?, historial_espacio = ?, disponibilidad_espacio = ?, tipo = ? 
             WHERE id_espacio = ?"
        );
        $stmtActualizar->bind_param("sisssi", $nombreEspacio, $capacidadEspacio, $historialEspacio, $disponibilidadEspacio, $tipoEspacio, $idEspacio);
        if (!$stmtActualizar->execute()) {
            throw new Exception($stmtActualizar->error);
        }
        $stmtActualizar->close();

        $usuarioIdSesion = $_SESSION['id_usuario'] ?? null;
        if ($usuarioIdSesion) {
            registrarAuditoria($con, $usuarioIdSesion, 'EDITAR_ESPACIO', "Se editó espacio (ID {$idEspacio}): {$nombreEspacio} ({$tipoEspacio})");
        }

        echo json_encode(["type" => "success", "message" => "Espacio actualizado correctamente."]);
        exit;
    }

    elseif ($accion === 'eliminar') {
        if (!$idEspacio) {
            throw new Exception("ID de espacio inválido para eliminar.");
        }

        // Verificar dependencias: recursos y reservas
        $stmtRecursos = $con->prepare("SELECT COUNT(*) FROM recurso WHERE id_espacio = ?");
        $stmtRecursos->bind_param("i", $idEspacio);
        $stmtRecursos->execute();
        $stmtRecursos->bind_result($cantidadRecursos);
        $stmtRecursos->fetch();
        $stmtRecursos->close();
        if ($cantidadRecursos > 0) {
            throw new Exception("No se puede eliminar el espacio: existen recursos asociados.");
        }

        $stmtReservas = $con->prepare("SELECT COUNT(*) FROM asignatura_docente_solicita_espacio WHERE id_espacio = ?");
        $stmtReservas->bind_param("i", $idEspacio);
        $stmtReservas->execute();
        $stmtReservas->bind_result($cantidadReservas);
        $stmtReservas->fetch();
        $stmtReservas->close();
        if ($cantidadReservas > 0) {
            throw new Exception("No se puede eliminar el espacio: existen reservas asociadas.");
        }

        $stmtBorrar = $con->prepare("DELETE FROM espacio WHERE id_espacio = ?");
        $stmtBorrar->bind_param("i", $idEspacio);
        if (!$stmtBorrar->execute()) {
            throw new Exception($stmtBorrar->error);
        }
        $stmtBorrar->close();

        $usuarioIdSesion = $_SESSION['id_usuario'] ?? null;
        if ($usuarioIdSesion) {
            registrarAuditoria($con, $usuarioIdSesion, 'ELIMINAR_ESPACIO', "Se eliminó espacio (ID {$idEspacio})");
        }

        echo json_encode(["type" => "success", "message" => "Espacio eliminado correctamente."]);
        exit;
    }

    else {
        throw new Exception("Acción no reconocida.");
    }

} catch (Exception $excepcion) {
    echo json_encode(["type" => "error", "message" => $excepcion->getMessage()]);
    exit;
}
?>
