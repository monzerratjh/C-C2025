<?php 
include('conexion_BD.php');
$conn = conectar_bd();

$id_usuario = $_POST['id_usuario'];
$ci_usuario = $_POST['ci_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$gmail_usuario = $_POST['gmail_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];
$cargo_usuario = $_POST['cargo_usuario'];
$contrasenia_usuario = $_POST['contrasenia_usuario'];

$sql = "UPDATE usuario SET 
        ci_usuario='$ci_usuario',
        nombre_usuario='$nombre_usuario', 
        apellido_usuario='$apellido_usuario', 
        gmail_usuario='$gmail_usuario',
        telefono_usuario='$telefono_usuario', 
        contrasenia_usuario='$contrasenia_usuario'
        WHERE id_usuario='$id_usuario'";
$query = mysqli_query($conn, $sql);

if($query){
    // Redirige de nuevo al listado
    header("Location: secretario-usuario.php");
    exit;
} else {
    echo "Error en la inserción: " . mysqli_error($conn);
}
?>