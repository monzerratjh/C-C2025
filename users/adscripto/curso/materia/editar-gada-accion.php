<?php
include('../../../../conexion.php');
$conn = conectar_bd();
session_start();

$stmt_gada = mysqli_prepare($conn, "SELECT * FROM grupo_asignatura_docente_aula");
mysqli_stmt_execute($stmt_gada);
$resultado_stmt_gada = mysqli_stmt_get_result($stmt_gada);
mysqli_stmt_close($stmt_gada);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_gada = $_POST['id_gada'];
    $nuevo_docente = $_POST['id_docente'];
    $nuevo_espacio = $_POST['id_espacio'];
    $nueva_asignatura = $_POST['id_asignatura'];
    $nuevo_grupo = $_POST['id_grupo'];


    $sql_actual = "SELECT * FROM grupo_asignatura_docente_aula WHERE id_gada = ?";
    $stmt = $conn->prepare($sql_actual);
    $stmt->bind_param("i", $id_gada);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila_actual = $resultado->fetch_assoc();


    $cambios = [];
    if ($fila_actual['id_docente'] != $nuevo_docente) $cambios[] = "id_docente = $nuevo_docente";
    if ($fila_actual['id_espacio'] != $nuevo_espacio) $cambios[] = "id_espacio = $nuevo_espacio";
    if ($fila_actual['id_asignatura'] != $nueva_asignatura) $cambios[] = "id_asignatura = $nueva_asignatura";
    if ($fila_actual['id_grupo'] != $nuevo_grupo) $cambios[] = "id_grupo = $nuevo_grupo";


    if (!empty($cambios)) {
        // Si hay cambios, actualizar solo esos campos
        $sql_update = "UPDATE grupo_asignatura_docente_aula SET " . implode(", ", $cambios) . " WHERE id_gada = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $id_gada);
        if ($stmt_update->execute()) {
            echo "¡Asignación actualizada correctamente!";
            header("Location: ./asociar-gada.php");
        } else {
            echo "Error al actualizar: " . $conn->error;
        }
    } else {
        echo "No se detectaron cambios.";
    }


    $stmt->close();
    $conn->close();


}
?>
