<?php
include('../../../conexion.php');
$conn = conectar_bd();
session_start();

$sql_materia = "SELECT * FROM asignatura";
$query = mysqli_query($conn, $sql_materia);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar-materia'])) {
    $nombre_materia = $_POST['insertar-materia'];
    $sql = "INSERT INTO asignatura (nombre_asignatura) VALUES (?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Error en prepare: " . mysqli_error($conn));
    } else {

    mysqli_stmt_bind_param($stmt, "s", $nombre_materia);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        // Redirige de nuevo al listado
        header("Location: ./carga-materias.php");
        exit;
    } else {
        echo "Error en la inserción: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST['id_asignatura'], $_POST['id_docente'], $_POST['id_espacio'])) {

    $id_asignatura = $_POST['id_asignatura'];
    $id_docente = $_POST['id_docente'];
    $id_espacio = $_POST['id_espacio'];

    $stmt = mysqli_prepare($conn, "
        INSERT INTO asign_docente_aula (id_asignatura, id_docente, id_aula)
        VALUES (?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iii", $id_asignatura, $id_docente, $id_espacio);
    $success = mysqli_stmt_execute($stmt);

    if (!$success) {
        echo "Error al insertar ADA: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

?>