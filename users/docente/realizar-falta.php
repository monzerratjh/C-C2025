<?php
include('..\..\..\conexion.php');
$conn = conectar_bd();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $materia_falta = $_POST['materia-falta'];
    $grupo_a_faltar = $_POST['grupo-a-faltar'];
    $fecha_a_faltar = $_POST['fecha-a-faltar'];
    $cant_horas_faltadas = $_POST['horas-faltadas'];
    $motivo_falta = $_POST['motivo-falta'];

    $sql_asig = "SELECT id_asignatura FROM asignatura WHERE nombre_asignatura = ?";
    $stmt_asig = $conn->prepare($sql_asig);
    $stmt_asig->bind_param("s", $materia_falta);
    $stmt_asig->execute();
    $result_asig = $stmt_asig->get_result();

    $sql_asig = "SELECT id_grupo FROM grupo WHERE nombre_grupo = ?";
    $stmt_asig = $conn->prepare($sql_asig);
    $stmt_asig->bind_param("s", $grupo_a_faltar);
    $stmt_asig->execute();
    $result_grupo = $stmt_grupo->get_result();


    if ($row_asig = $result_asig->fetch_assoc()) {
        $id_asignatura = $row_asig['id_asignatura'];
    } else {
        die("Error: no se encontró la materia '$materia_falta'.");
    }


    $stmt = $con->prepare("INSERT INTO falta_docente 
        (id_falta, id_asignatura, id_grupo, fecha_falta, cantidad_horas, motivo_falta, id_docente)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisisi",$id_asignatura, $id_docente, $id_grupo, $id_grupo, $fecha_a_faltar, $cant_horas_faltadas, $motivo_falta);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Falta registrada correctamente";
    } else {
        echo "Error al registrar la falta";
    }
    
}
?>