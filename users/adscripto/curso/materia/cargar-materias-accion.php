<?php
include('../../../../conexion.php');
$conn = conectar_bd();
session_start();

$stmt_materia = mysqli_prepare($conn, "SELECT * FROM asignatura");
mysqli_stmt_execute($stmt_materia);
$resultado_stmt_materia = mysqli_stmt_get_result($stmt_materia);
mysqli_stmt_close($stmt_materia);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar-materia'])) {
    $nombre_materia = $_POST['insertar-materia'];   
    $validacion_rslt = validacion($nombre_materia);
    if($validacion_rslt == true) {
        $sql = "INSERT INTO asignatura (nombre_asignatura) VALUES (?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            die("Error en prepare: " . mysqli_error($conn));
        } else {

        mysqli_stmt_bind_param($stmt, "s", $nombre_materia);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // Redirige de nuevo al listado
            header("Location: ./carga-materias.php?msg=InsercionExitosa");
            exit;
        } else {
            echo "Error en la inserción: " . mysqli_error($conn);
            header("Location: ./carga-materias.php?error=InsercionFallida");
            exit;
        }

        mysqli_stmt_close($stmt);
        }
    }
    
}
function validacion($nombre_materia) {
    if(empty($nombre_materia)) {
        header("Location: ./carga-materias.php?error=CampoVacio");
    } else if(!preg_match("/^[a-zA-ZÀ-ÿ\s]{1,50}$/", $nombre_materia)) {
        header("Location: ./carga-materias.php?error=NombreInvalido");
        exit;
    } else {
        // Verificar si la asignatura ya existe
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