<?php
include('../../../../conexion.php');
$conn = conectar_bd();
session_start();
//$id_adscripto = $_SESSION['id_adscripto'] ?? null;

$sql_gada = "SELECT * FROM grupo_asignatura_docente_aula";
$query = mysqli_query($conn, $sql_gada);

if ($_SERVER['REQUEST_METHOD'] === 'POST' 
   /* && isset($_POST['id_asignatura'], $_POST['id_docente'], $_POST['id_espacio'], $_POST['id_grupo'])*/) {

    $id_grupo = $_POST['id_grupo'];
    $id_asignatura = $_POST['id_asignatura'];
    $id_docente = $_POST['id_docente'];
    $id_espacio = $_POST['id_espacio'];
    

    var_dump($id_grupo, $id_asignatura, $id_docente, $id_espacio);

    $stmt = mysqli_prepare($conn, "
        INSERT INTO grupo_asignatura_docente_aula (id_grupo, id_asignatura, id_docente, id_espacio)
        VALUES (?, ?, ?, ?)
    ");
    var_dump(mysqli_stmt_bind_param($stmt, "iiii", $id_grupo, $id_asignatura, $id_docente, $id_espacio));
    $success = mysqli_stmt_execute($stmt);
    var_dump($success);

    if ($success) {
        // Redirige de nuevo al listado
        header("Location: ./asociar-gada.php");
    } else {
        echo "Error al insertar GADA: " . mysqli_error($conn);
        exit;
    }

    mysqli_stmt_close($stmt);
}

?>