<?php
include('../../../../conexion.php');
$conn = conectar_bd();

$id_asignatura = $_POST['id_asignatura'] ?? null;
$nombre_asignatura = $_POST['editar-materia'];

$validacion_rslt = validacion($nombre_asignatura);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_asignatura'], $_POST['editar-materia'])) {

    // Preparamos el UPDATE
    $stmt = $conn->prepare("UPDATE asignatura SET nombre_asignatura = ? WHERE id_asignatura = ?");
    $stmt->bind_param("si", $nombre_asignatura, $id_asignatura);
    
    if($validacion_rslt === true) {
        // Redirigimos al listado de materias
        $stmt->execute();
        header("Location: ./carga-materias.php?msg=EdicionExitosa");
        exit;

    } else {
        header("Location: ./carga-materias.php?error=ActualizacionFallida");
        exit;
    }
    /*if ($stmt->execute()) {
        // Redirigimos al listado de materias
        header("Location: ./carga-materias.php?msg=EdicionExitosa");
        exit;
    } else {
        echo "Error al actualizar la asignatura: " . $stmt->error;
        header("Location: ./carga-materias.php?error=ActualizacionFallida");
        exit;
    }*/

    $stmt->close();
} else {
   
}

function validacion($nombre_asignatura) {
    if(empty($nombre_asignatura)) {
        header("Location: ./carga-materias.php?error=CampoVacio");
    } else if(!preg_match("/^[a-zA-ZÀ-ÿ\s]{1,50}$/", $nombre_asignatura)) {
        header("Location: ./carga-materias.php?error=NombreInvalido");
        exit;
    } else {
        // Verificar si la asignatura ya existe
        $conn = conectar_bd();
        $query_val = "SELECT * FROM asignatura WHERE nombre_asignatura = ?";
        $stmt = mysqli_prepare($conn, $query_val);
        mysqli_stmt_bind_param($stmt, "s", $nombre_asignatura);
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
