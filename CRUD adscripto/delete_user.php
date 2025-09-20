<?php

include("connection.php");
$con = connection();

// Recibir el id_usuario desde la URL
$id = $_GET["id_usuario"];

// Sentencia SQL ajustada a tu tabla real
$sql = "DELETE  FROM usuario WHERE id_usuario='$id'";
$query = mysqli_query($con, $sql);

if ($query) {
    Header("Location: index.php"); // redirige si se elimina correctamente
    echo "Eliminado correctamente";
    exit();
} else {
    echo "Error al eliminar: " . mysqli_error($con);
}

?>
