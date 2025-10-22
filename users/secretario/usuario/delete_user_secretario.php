<?php
include('./../../../conexion.php');
$conn = conectar_bd();

$id_usuario = $_GET['id_usuario'] ?? null; // Si no viene nada, queda null
if (!$id_usuario) {
    echo "No se proporcionó un ID de usuario.";
    exit;
}

$sql = "DELETE FROM usuario WHERE id_usuario= ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_usuario);
        $execute = mysqli_stmt_execute($stmt);

        if ($execute) {
            // Eliminación exitosa, redirigir con mensaje
            header("Location: ./secretario-usuario.php?msg=EliminacionExitosa");
            exit();
        } else {
            echo "Error al eliminar el usuario: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conn);
    }

mysqli_close($conn);

?>