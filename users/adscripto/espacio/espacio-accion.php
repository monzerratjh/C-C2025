<?php
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

$tiposPermitidos = ['Salón', 'Aula', 'Laboratorio'];

function obtenerValoresDisponibilidad($conexion) {
    $resultado = $conexion->query("SHOW COLUMNS FROM espacio LIKE 'disponibilidad_espacio'");
    $fila = $resultado->fetch_assoc();
    preg_match_all("/'([^']+)'/", $fila['Type'], $coincidencias);
    return $coincidencias[1];
}

function validarNombreEspacio($nombre) {
    return preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \-_,().]{2,50}$/u', $nombre);
}

/* function registrarAuditoria($conexion, $usuarioId, $accionRealizada, $detalleAccion) {
    $stmt = $conexion->prepare("INSERT INTO auditoria_acciones (id_usuario, accion, detalle_accion, fecha_hora) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $usuarioId, $accionRealizada, $detalleAccion);
    $stmt->execute();
    $stmt->close();
}*/

try {
    if ($accion === 'insertar' || $accion === 'editar') {
        $valoresDisponibilidad = obtenerValoresDisponibilidad($con);

        if ($nombreEspacio === '' || !validarNombreEspacio($nombreEspacio)) {
            throw new Exception("Nombre de espacio inválido (permitidos: letras, números, - _ , . ()).");
        }
        if ($capacidadEspacio < 1 || $capacidadEspacio > 50) {
            throw new Exception("Capacidad inválida (1-50).");
        }
        if (!in_array($disponibilidadEspacio, $valoresDisponibilidad, true)) {
            throw new Exception("Disponibilidad inválida.");
        }
        if (!in_array($tipoEspacio, $tiposPermitidos, true)) {
            throw new Exception("Tipo de espacio inválido.");
        }
    }

    if ($accion === 'insertar') {
        $stmtInsert = $con->prepare("INSERT INTO espacio (nombre_espacio, capacidad_espacio, historial_espacio, disponibilidad_espacio, tipo) 
                                     VALUES (?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("sisss", $nombreEspacio, $capacidadEspacio, $historialEspacio, $disponibilidadEspacio, $tipoEspacio);
        $stmtInsert->execute();
        $stmtInsert->close();

        /*registrarAuditoria($con, $_SESSION['id_usuario'] ?? null, 'INSERTAR_ESPACIO', "Insertó espacio: {$nombreEspacio}");
*/

        echo json_encode(["type" => "success", "message" => "Espacio agregado correctamente."]);
        exit;
    }

    elseif ($accion === 'editar') {
        if (!$idEspacio) throw new Exception("ID de espacio inválido para editar.");

        $stmtActualizar = $con->prepare("UPDATE espacio 
                                         SET nombre_espacio=?, capacidad_espacio=?, historial_espacio=?, disponibilidad_espacio=?, tipo=? 
                                         WHERE id_espacio=?");
        $stmtActualizar->bind_param("sisssi", $nombreEspacio, $capacidadEspacio, $historialEspacio, $disponibilidadEspacio, $tipoEspacio, $idEspacio);
        $stmtActualizar->execute();
        $stmtActualizar->close();

        //registrarAuditoria($con, $_SESSION['id_usuario'] ?? null, 'EDITAR_ESPACIO', "Editó espacio (ID {$idEspacio}): {$nombreEspacio}");
        
        echo json_encode(["type" => "success", "message" => "Espacio actualizado correctamente."]);
        exit;
    }

    elseif ($accion === 'eliminar') {
        if (!$idEspacio) throw new Exception("ID de espacio inválido para eliminar.");

        $stmtRecursos = $con->prepare("SELECT COUNT(*) FROM recurso WHERE id_espacio = ?");
        $stmtRecursos->bind_param("i", $idEspacio);
        $stmtRecursos->execute();
        $stmtRecursos->bind_result($cantidadRecursos);
        $stmtRecursos->fetch();
        $stmtRecursos->close();
        if ($cantidadRecursos > 0) throw new Exception("No se puede eliminar: hay recursos asociados.");

        $stmtReservas = $con->prepare("SELECT COUNT(*) FROM asignatura_docente_solicita_espacio WHERE id_espacio = ?");
        $stmtReservas->bind_param("i", $idEspacio);
        $stmtReservas->execute();
        $stmtReservas->bind_result($cantidadReservas);
        $stmtReservas->fetch();
        $stmtReservas->close();
        if ($cantidadReservas > 0) throw new Exception("No se puede eliminar: hay reservas asociadas.");

        $stmtBorrar = $con->prepare("DELETE FROM espacio WHERE id_espacio = ?");
        $stmtBorrar->bind_param("i", $idEspacio);
        $stmtBorrar->execute();
        $stmtBorrar->close();

        //registrarAuditoria($con, $_SESSION['id_usuario'] ?? null, 'ELIMINAR_ESPACIO', "Eliminó espacio (ID {$idEspacio})");
        echo json_encode(["type" => "success", "message" => "Espacio eliminado correctamente."]);
        exit;
    }

    else {
        throw new Exception("Acción no reconocida.");
    }

} catch (Exception $e) {
    echo json_encode(["type" => "error", "message" => $e->getMessage()]);
    exit;
}
?>
