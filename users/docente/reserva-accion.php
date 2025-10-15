<?php
include('./../../conexion.php');
session_start();
$con = conectar_bd();

header('Content-Type: application/json; charset=utf-8');

$accion = $_POST['accion'] ?? '';
//$idReserva = intval($_POST['id_reserva'] ?? 0);
$idAsignatura = intval($_POST['id_asignatura'] ?? 0);
$idEspacio = intval($_POST['id_espacio'] ?? 0);
$idHorario = intval($_POST['id_horario_clase'] ?? 0);
$idDocente = $_SESSION['id_usuario'] ?? 1;

try {
    //if (!$idDocente) throw new Exception("Debe iniciar sesión.");

    if ($accion === 'insertar') {
        // Verificar disponibilidad
        $sql = "SELECT COUNT(*) AS ocupadas FROM asignatura_docente_solicita_espacio 
                WHERE id_espacio = ? AND id_horario_clase = ? AND estado_reserva IN ('pendiente','aceptada')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $idEspacio, $idHorario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res['ocupadas'] > 0) throw new Exception("El espacio ya está reservado en ese horario.");

        // Insertar reserva
        $stmt = $con->prepare("
            INSERT INTO asignatura_docente_solicita_espacio 
            (id_asignatura, id_docente, id_horario_clase, id_espacio, estado_reserva)
            VALUES (?, ?, ?, ?, 'pendiente')
        ");
        $stmt->bind_param("iiii", $idAsignatura, $idDocente, $idHorario, $idEspacio);
        if (!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type" => "success", "message" => "Reserva solicitada correctamente."]);
        exit;
    }

    elseif ($accion === 'cancelar') {
        if (!$idReserva) throw new Exception("Reserva inválida.");

        $stmt = $con->prepare("UPDATE asignatura_docente_solicita_espacio SET estado_reserva = 'cancelada' WHERE id_reserva = ?");
        $stmt->bind_param("i", $idReserva);
        if (!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type" => "success", "message" => "Reserva cancelada correctamente."]);
        exit;
    }

    else {
        throw new Exception("Acción no válida.");
    }
} catch (Exception $e) {
    echo json_encode(["type" => "error", "message" => $e->getMessage()]);
}
?>
