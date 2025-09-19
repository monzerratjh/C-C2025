<?php
include("connection.php");
$conn = connection();

$accion = $_POST['accion'] ?? '';

if($accion == 'insertar'){
    $orientacion = $_POST['orientacion'];
    $turno = $_POST['turno'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $id_adscripto = $_POST['id_adscripto'];
    $id_secretario = $_POST['id_secretario'];

    $stmt = $conn->prepare("INSERT INTO grupo (orientacion_grupo, turno_grupo, nombre_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiii",$orientacion,$turno,$nombre,$cantidad,$id_adscripto,$id_secretario);
    $stmt->execute();
    $stmt->close();
    header("Location: grupo_secretario.php?message=Grupo creado");
}

if($accion == 'editar'){
    $id = $_POST['id_grupo'];
    $orientacion = $_POST['orientacion'];
    $turno = $_POST['turno'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];

    $stmt = $conn->prepare("UPDATE grupo SET orientacion_grupo=?, turno_grupo=?, nombre_grupo=?, cantidad_alumno_grupo=? WHERE id_grupo=?");
    $stmt->bind_param("sssii",$orientacion,$turno,$nombre,$cantidad,$id);
    $stmt->execute();
    $stmt->close();
    header("Location: grupo_secretario.php?message=Grupo actualizado");
}

if($accion == 'eliminar'){
    $id = $_POST['id_grupo'];
    $stmt = $conn->prepare("DELETE FROM grupo WHERE id_grupo=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
    header("Location: grupo_secretario.php?message=Grupo eliminado");
}
?>
