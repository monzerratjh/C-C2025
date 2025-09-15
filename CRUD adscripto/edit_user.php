<?php

include("connection.php");
$con = connection();

// Recibir datos del formulario
$id = $_POST["id_usuario"];
$nombre = $_POST['nombre_usuario'];
$apellido = $_POST['apellido_usuario'];
$gmail = $_POST['gmail_usuario'];
$telefono = $_POST['telefono_usuario'];
$cedula = $_POST['ci_usuario']; 

// Sentencia SQL ajustada a la tabla "usuario"
$sql = "UPDATE usuario 
        SET nombre_usuario='$nombre', 
            apellido_usuario='$apellido', 
            gmail_usuario='$gmail', 
            telefono_usuario='$telefono',
            ci_usuario='$cedula'
        WHERE id_usuario='$id'";

$query = mysqli_query($con, $sql);

// Redirección si todo va bien
if($query){
    Header("Location: index.php");
    exit();
} else {
    echo "Error al actualizar: " . mysqli_error($con);
}

?>
