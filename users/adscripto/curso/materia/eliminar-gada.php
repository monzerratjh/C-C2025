<?php 
include('../../../../conexion.php');
$conn = conectar_bd();
if (isset($_GET['id_gada'])) {
    $id_gada = intval($_GET['id_gada']); // Convertir a entero por seguridad

    // Consulta para eliminar
    $sql = "DELETE FROM grupo_asignatura_docente_aula WHERE id_gada = ?";
    
    // Preparar statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_gada);
        $execute = mysqli_stmt_execute($stmt);

        if ($execute) {
            // Eliminación exitosa, redirigir con mensaje
            header("Location: ./asociar-gada.php?msg=EliminacionExitosa");
            exit();
        } else {
            echo "Error al eliminar la asignación: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conn);
    }
} else {
    echo "No se recibió un ID válido.";
}

mysqli_close($conn);
?>