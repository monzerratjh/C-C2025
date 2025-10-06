<?php
include('../../../conexion.php');
$conexion = conectar_bd();
session_start();

// Recibir datos del formulario
$accion = $_POST['accion'] ?? '';
$id_horario = $_POST['id_horario'] ?? null;
$id_grupo = $_POST['id_grupo'] ?? '';
$dia = $_POST['dia'] ?? '';
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';
$turno = $_POST['turno'] ?? '';
$id_secretario = $_POST['id_secretario'] ?? 1;

// Validaciones básicas
if ($accion === '') {
    echo json_encode(['type' => 'error', 'message' => 'Acción no definida.']);
    exit;
}

try {
    if ($accion === 'insertar') {
        $sentencia = $conexion->prepare("
            INSERT INTO horario_clase (id_grupo, dia, hora_inicio, hora_fin, turno, id_secretario)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $sentencia->bind_param("issssi", $id_grupo, $dia, $hora_inicio, $hora_fin, $turno, $id_secretario);
        $sentencia->execute();

        echo json_encode(['type' => 'success', 'message' => 'Horario agregado correctamente.']);

    } elseif ($accion === 'editar' && $id_horario) {
        $sentencia = $conexion->prepare("
            UPDATE horario_clase
            SET id_grupo = ?, dia = ?, hora_inicio = ?, hora_fin = ?, turno = ?, id_secretario = ?
            WHERE id_horario = ?
        ");
        $sentencia->bind_param("issssii", $id_grupo, $dia, $hora_inicio, $hora_fin, $turno, $id_secretario, $id_horario);
        $sentencia->execute();

        echo json_encode(['type' => 'success', 'message' => 'Horario actualizado correctamente.']);

    } elseif ($accion === 'eliminar' && $id_horario) {
        $sentencia = $conexion->prepare("DELETE FROM horario_clase WHERE id_horario = ?");
        $sentencia->bind_param("i", $id_horario);
        $sentencia->execute();

        echo json_encode(['type' => 'success', 'message' => 'Horario eliminado correctamente.']);

    } else {
        echo json_encode(['type' => 'error', 'message' => 'Datos incompletos o acción no reconocida.']);
    }

} catch (Exception $error) {
    echo json_encode(['type' => 'error', 'message' => 'Error en la base de datos: ' . $error->getMessage()]);
}

$conexion->close();
?>
