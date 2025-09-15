<?php
include("connection.php");
$con = connection();

// id_usuario es AUTO_INCREMENT en tu BD, así que no lo seteamos
$nombre   = $_POST['nombre_usuario'];
$apellido = $_POST['apellido_usuario'];
$gmail    = $_POST['gmail_usuario'];
$telefono = $_POST['telefono_usuario'];

// Insert ajustado a la estructura real de la tabla "usuario"
$sql = "INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario) 
        VALUES ('$nombre', '$apellido', '$gmail', '$telefono')";

$query = mysqli_query($con, $sql);

if ($query) {
    Header("Location: index.php");
    exit();
} else {
    echo "Error al insertar: " . mysqli_error($con);
}
?>
