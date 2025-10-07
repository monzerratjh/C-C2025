<?php
include('../../../conexion.php');
session_start();
$con = conectar_bd();
header("Content-Type: application/json");

$accion = $_POST['accion'] ?? '';
$id_horario = $_POST['id_horario_clase'] ?? null;
$dia = $_POST['dia'] ?? '';
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';
$turno = $_POST['turno'] ?? '';
$id_secretario = $_POST['id_secretario'] ?? 1;

try {
    if($accion === 'insertar') {
        $stmt = $con->prepare("INSERT INTO horario_clase (dia, hora_inicio, hora_fin, turno, id_secretario) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $dia, $hora_inicio, $hora_fin, $turno, $id_secretario);
        if(!$stmt->execute()) throw new Exception($stmt->error);
        echo json_encode(["type"=>"success","message"=>"Horario agregado correctamente"]);
    } elseif($accion === 'editar') {
        $stmt = $con->prepare("UPDATE horario_clase SET dia=?, hora_inicio=?, hora_fin=?, turno=? WHERE id_horario_clase=?");
        $stmt->bind_param("ssssi", $dia, $hora_inicio, $hora_fin, $turno, $id_horario);
        if(!$stmt->execute()) throw new Exception($stmt->error);
        echo json_encode(["type"=>"success","message"=>"Horario actualizado correctamente"]);
    } elseif($accion === 'eliminar') {
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
