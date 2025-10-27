<?php
include('../../../../conexion.php');
$conn = conectar_bd();
session_start();
//$id_adscripto = $_SESSION['id_adscripto'] ?? null;

$stmt_gada = mysqli_prepare($conn, "SELECT * FROM grupo_asignatura_docente_aula");
mysqli_stmt_execute($stmt_gada);
$resultado_stmt_gada = mysqli_stmt_get_result($stmt_gada);
mysqli_stmt_close($stmt_gada);

if ($_SERVER['REQUEST_METHOD'] === 'POST' 
   /* && isset($_POST['id_asignatura'], $_POST['id_docente'], $_POST['id_espacio'], $_POST['id_grupo'])*/) {

    $id_grupo = $_POST['id_grupo'];
    $id_asignatura = $_POST['id_asignatura'];
    $id_docente = $_POST['id_docente'];
    $id_espacio = $_POST['id_espacio'];

    $validacion_result = validaciones($id_grupo, $id_asignatura, $id_docente, $id_espacio);
    if($validacion_result !== true) {
        exit;
    } else {

        $stmt = mysqli_prepare($conn, "
            INSERT INTO grupo_asignatura_docente_aula (id_grupo, id_asignatura, id_docente, id_espacio)
            VALUES (?, ?, ?, ?)
        ");
        mysqli_stmt_bind_param($stmt, "iiii", $id_grupo, $id_asignatura, $id_docente, $id_espacio);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // Redirige de nuevo al listado
            header("Location: ./asociar-gada.php");
        } else {
            echo "Error al insertar GADA: " . mysqli_error($conn);
            exit;
        }

        mysqli_stmt_close($stmt);
    }

}


function validaciones($id_grupo, $id_asignatura, $id_docente, $id_espacio) {
        // validaciones de si está duplicado o si no existe  en la BD
        $query_val = "
        SELECT * FROM grupo_asignatura_docente_aula 
        WHERE id_grupo = ? AND id_asignatura = ? 
        AND id_docente = ? AND id_espacio = ?
    ";
    $conn = conectar_bd();
    $stmt = mysqli_prepare($conn, $query_val);
    mysqli_stmt_bind_param($stmt, "iiii", $id_grupo, $id_asignatura, $id_docente, $id_espacio);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if (mysqli_num_rows($result) > 0) {
        header("Location: ./asociar-gada.php?error=AsignacionDuplicada");
        return false;
    }

    return true;
    }

?>
