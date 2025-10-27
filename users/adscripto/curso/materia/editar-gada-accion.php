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

    $validaciones_rslt = validaciones($nuevo_grupo, $nueva_asignatura, $nuevo_docente, $nuevo_espacio);

    if($validaciones_rslt == true) {
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
                header("Location: ./asociar-gada.php?msg=ActualizacionExitosa");
            } else {
                header("Location: ./asociar-gada.php?error=FalloActualizacion");
            }
        } else {
            echo "No se detectaron cambios.";
        }

        $stmt->close();
        $conn->close();
    } else {
        header("Location: ./asociar-gada.php?error=AsignacionDuplicada");
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
