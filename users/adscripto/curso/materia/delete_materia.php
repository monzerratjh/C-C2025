<?php 
include('../../../../conexion.php');
$conn = conectar_bd();

$id_asignatura = $_GET['id_asignatura'] ?? null; // Si no viene nada, queda null
if (!$id_asignatura) {
    echo "No se proporcionó un ID de asignatura.";
    exit;
}

$sql = "DELETE FROM asignatura WHERE id_asignatura=?";
$stmt = mysqli_prepare($conn, $sql);
 if ($stmt === false) {
        die("Error en prepare: " . mysqli_error($conn));
    } else {
        mysqli_stmt_bind_param($stmt, "i", $id_asignatura);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            echo "Asignatura eliminada correctamente.";
        } else {
            echo "Error al eliminar la asignatura: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    }

if($success){
    // Redirige de nuevo al listado
    header("Location: carga-materias.php");
    exit;
} else {
    echo "Error en el SQL " . mysqli_error($conn);
}
?>