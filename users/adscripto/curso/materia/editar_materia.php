<?php
include('../../../../conexion.php');
$conn = conectar_bd();

$id_asignatura = $_POST['id_asignatura'] ?? null;
$nombre_asignatura = $_POST['editar-materia'];

// Verificamos que llegue el POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_asignatura'], $_POST['editar-materia'])) {

    // Preparamos el UPDATE
    $stmt = $conn->prepare("UPDATE asignatura SET nombre_asignatura = ? WHERE id_asignatura = ?");
    $stmt->bind_param("si", $nombre_asignatura, $id_asignatura);

    if ($stmt->execute()) {
        // Redirigimos al listado de materias
        header("Location: ./carga-materias.php");
        exit;
    } else {
        echo "Error al actualizar la asignatura: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Faltan datos para editar la asignatura.".$nombre_asignatura.$id_asignatura;
}
?>
