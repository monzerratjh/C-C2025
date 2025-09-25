<?php
include('conexion_BD.php');
$conn = conectar_bd();

$id_usuario = $_GET['id_usuario'] ?? null; // Si no viene nada, queda null
if (!$id_usuario) {
    echo "No se proporcionó un ID de usuario.";
    exit;
}

$sql = "DELETE FROM usuario WHERE id_usuario='$id_usuario'";
$query = mysqli_query($conn, $sql);

if($query){
    // Redirige de nuevo al listado
    header("Location: secretario-usuario.php");
    exit;
} else {
    echo "Error en el SQL " . mysqli_error($conn);
}
?>