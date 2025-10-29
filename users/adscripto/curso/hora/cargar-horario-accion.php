<?php
include('./../../../../conexion.php');
$con = conectar_bd();

$accion = $_POST['accion'] ?? '';
$id_grupo = $_POST['id_grupo'] ?? null;
$id_gada = $_POST['id_gada'] ?? null;
$id_horario_clase = $_POST['id_horario_clase'] ?? null;
$dia = $_POST['dia'] ?? null;
$id_horario_asignado = $_POST['id_horario_asignado'] ?? null;

// Forzar respuesta JSON
header('Content-Type: application/json');

// Validación general para insertar o editar
if (in_array($accion, ['insertar', 'editar'])) {
    if (!$id_grupo || !$id_gada || !$id_horario_clase || !$dia) {
        echo json_encode(["type" => "error", "message" => "Complete todos los campos."]);
        exit;
    }
}

switch ($accion) {
    case 'insertar':
        // Verificar duplicado
        $query = "
          SELECT ha.id_horario_asignado
          FROM horario_asignado ha
          JOIN grupo_asignatura_docente_aula gada ON ha.id_gada = gada.id_gada
          WHERE ha.id_horario_clase = '$id_horario_clase'
            AND ha.dia = '$dia'
            AND gada.id_grupo = '$id_grupo'
        ";
        $existe = mysqli_query($con, $query);

        if (mysqli_num_rows($existe) > 0) {
            echo json_encode(["type" => "error", "message" => "Este horario ya está asignado para ese día."]);
            exit;
        }

        $insert = mysqli_query($con, "
          INSERT INTO horario_asignado (id_horario_clase, id_gada, dia)
          VALUES ('$id_horario_clase', '$id_gada', '$dia')
        ");

        echo json_encode([
            "type" => $insert ? "success" : "error",
            "message" => $insert ? "Horario guardado correctamente." : "Error al guardar el horario."
        ]);
        exit;

    case 'editar':
        if (!$id_horario_asignado) {
            echo json_encode(["type" => "error", "message" => "ID de horario a editar no encontrado."]);
            exit;
        }

        // Evitar duplicados al editar
        $query_dup = "
          SELECT ha.id_horario_asignado
          FROM horario_asignado ha
          JOIN grupo_asignatura_docente_aula gada ON ha.id_gada = gada.id_gada
          WHERE ha.id_horario_clase='$id_horario_clase'
            AND ha.dia='$dia'
            AND gada.id_grupo='$id_grupo'
            AND ha.id_horario_asignado!='$id_horario_asignado'
        ";
        $res_dup = mysqli_query($con, $query_dup);
        if (mysqli_num_rows($res_dup) > 0) {
            echo json_encode(["type" => "error", "message" => "Ya existe un horario asignado igual."]);
            exit;
        }

        $update = mysqli_query($con, "
          UPDATE horario_asignado
          SET id_horario_clase='$id_horario_clase', id_gada='$id_gada', dia='$dia'
          WHERE id_horario_asignado='$id_horario_asignado'
        ");

        echo json_encode([
            "type" => $update ? "success" : "error",
            "message" => $update ? "Horario actualizado correctamente." : "Error al actualizar el horario."
        ]);
        exit;

    case 'eliminar':
        if (!$id_horario_asignado) {
            echo json_encode(["type" => "error", "message" => "ID de horario a eliminar no encontrado."]);
            exit;
        }

        $delete = mysqli_query($con, "DELETE FROM horario_asignado WHERE id_horario_asignado='$id_horario_asignado'");

        echo json_encode([
            "type" => $delete ? "success" : "error",
            "message" => $delete ? "Horario eliminado correctamente." : "Error al eliminar el horario."
        ]);
        exit;

    default:
        echo json_encode(["type" => "error", "message" => "Acción no reconocida."]);
        exit;
}
?>