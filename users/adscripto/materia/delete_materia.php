<?php 
include('..\..\..\conexion.php');
$conn = conectar_bd();

$id_asignatura = $_GET['id_asignatura'] ?? null; // Si no viene nada, queda null
if (!$id_asignatura) {
    echo "No se proporcionó un ID de asignatura.";
    exit;
}

$sql = "DELETE FROM asignatura WHERE id_asignatura='$id_asignatura'";
$query = mysqli_query($conn, $sql);

if($query){
    // Redirige de nuevo al listado
    header("Location: cargar-materias.php");
    exit;
} else {
    echo "Error en el SQL " . mysqli_error($conn);
}
?>