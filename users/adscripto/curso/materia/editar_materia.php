<?php
include('../../../../conexion.php');
$conn = conectar_bd();

$id_asignatura = $_POST['id_asignatura'] ?? null;
$nombre_asignatura = $_POST['editar-materia'];

$validacion_rslt = validacion($nombre_materia);
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
        header("Location: ./carga-materias.php?error=ActualizacionFallida");
        exit;
    }

    $stmt->close();
} else {
   
}

function validacion($nombre_materia) {
    if(empty($nombre_materia)) {
        header("Location: ./carga-materias.php?error=CampoVacio");
    } else if(!preg_match("/^[a-zA-ZÀ-ÿ\s]{1,50}$/", $nombre_materia)) {
        header("Location: ./carga-materias.php?error=NombreInvalido");
        exit;
    } else {
        // Verificar si la materia ya existe
        $conn = conectar_bd();
        $query_val = "SELECT * FROM asignatura WHERE nombre_asignatura = ?";
        $stmt = mysqli_prepare($conn, $query_val);
        mysqli_stmt_bind_param($stmt, "s", $nombre_materia);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if (mysqli_num_rows($result) > 0) {
            header("Location: ./carga-materias.php?error=MateriaDuplicada");
            exit;
        } else {
            return true;
        }
    }
} 

?>
