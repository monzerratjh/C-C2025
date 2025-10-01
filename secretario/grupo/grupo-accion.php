<?php
include('../../conexion.php');
$con = conectar_bd();
session_start();

// Recibimos los datos del formulario
$accion       = $_POST['accion'] ?? '';
$id_grupo     = $_POST['id_grupo'] ?? null;
$nombre       = $_POST['nombre'] ?? '';
$orientacion  = $_POST['orientacion'] ?? '';
$turno        = $_POST['turno'] ?? '';
$cantidad     = $_POST['cantidad'] ?? '';
$id_adscripto = $_POST['id_adscripto'] ?? '';
$id_secretario = $_SESSION['id_secretario'] ?? 1; // fallback a 1 si no hay sesión

$message = '';
$type = 'success';

try {
    if($accion === 'insertar') {
        $stmt = $con->prepare("INSERT INTO grupo (nombre_grupo, orientacion_grupo, turno_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $nombre, $orientacion, $turno, $cantidad, $id_adscripto, $id_secretario);
        if(!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $message = "Grupo agregado correctamente.";
    } elseif($accion === 'editar') {
        $stmt = $con->prepare("UPDATE grupo SET nombre_grupo=?, orientacion_grupo=?, turno_grupo=?, cantidad_alumno_grupo=?, id_adscripto=? WHERE id_grupo=?");
        $stmt->bind_param("sssiii", $nombre, $orientacion, $turno, $cantidad, $id_adscripto, $id_grupo);
        if(!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $message = "Grupo actualizado correctamente.";
    } elseif($accion === 'eliminar') {
        $stmt = $con->prepare("DELETE FROM grupo WHERE id_grupo=?");
        $stmt->bind_param("i", $id_grupo);
        if(!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $message = "Grupo eliminado correctamente.";
    } else {
        throw new Exception("Acción no reconocida.");
    }
} catch(Exception $e) {
    $message = "Error: ".$e->getMessage();
    $type = 'error';
}

// Redirigimos de vuelta con SweetAlert usando GET
header("Location: secretario-grupo.php?message=".urlencode($message)."&type=".$type);
exit;
