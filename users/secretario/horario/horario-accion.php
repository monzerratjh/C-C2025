<?php
include('../../../conexion.php');
session_start();
$con = conectar_bd();
header("Content-Type: application/json");

// Recibir datos POST
$accionHorario = $_POST['accionHorario'] ?? '';
$id_horario = $_POST['id_horario_clase'] ?? null;
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';
$id_secretario = $_POST['id_secretario'] ?? 1;

try {
    if($accionHorario === 'insertar') {
        // Validar datos antes de insertar
        if( empty($hora_inicio) || empty($hora_fin) ) {
            throw new Exception("Faltan datos para insertar el horario");
        }

        $stmt = $con->prepare("INSERT INTO horario_clase (hora_inicio, hora_fin, id_secretario) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $hora_inicio, $hora_fin, $id_secretario);
        if(!$stmt->execute()) throw new Exception($stmt->error);
        
        echo json_encode(["type"=>"success","message"=>"Horario agregado correctamente"]);

    } elseif($accionHorario === 'editar') {
        if(empty($id_horario)) throw new Exception("ID de horario no especificado");
        $stmt = $con->prepare("UPDATE horario_clase SET hora_inicio=?, hora_fin=? WHERE id_horario_clase=?");
        $stmt->bind_param("ssi", $hora_inicio, $hora_fin, $id_horario);
        if(!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type"=>"success","message"=>"Horario actualizado correctamente"]);

    } elseif($accionHorario === 'eliminar') {
        if(empty($id_horario)) throw new Exception("ID de horario no especificado");
        $stmt = $con->prepare("DELETE FROM horario_clase WHERE id_horario_clase=?");
        $stmt->bind_param("i", $id_horario);
        if(!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type"=>"success","message"=>"Horario eliminado correctamente"]);

    } else {
        throw new Exception("Acción no reconocida");
    }
} catch(Exception $e){
    echo json_encode(["type"=>"error","message"=>"Error: ".$e->getMessage()]);
}
