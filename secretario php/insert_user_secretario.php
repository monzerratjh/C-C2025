<?php
include('..\conexion.php');
$conn = conectar_bd();


$ci_usuario = $_POST['ci_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$gmail_usuario = $_POST['gmail_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];
$cargo_usuario = $_POST['cargo_usuario'];
$contrasenia_usuario = $_POST['contrasenia_usuario'];

$sql = "INSERT INTO usuario 
      (ci_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, contrasenia_usuario) 
    VALUES('$ci_usuario', '$nombre_usuario', '$apellido_usuario', '$gmail_usuario', '$telefono_usuario','$cargo_usuario','$contrasenia_usuario')";
$query = mysqli_query($conn, $sql);
$idUsuario = $conn->insert_id;

if ($cargo_usuario === "Docente") {
    $grado = $_POST['grado_docente_usuario'] ?? null;
    $sql_docente = "INSERT INTO docente (grado_docente, id_usuario) 
                    VALUES ($grado, $idUsuario);";
    $queryResult = mysqli_query($conn, $sql_docente);

} elseif ($cargo_usuario === "Adscripto") {
    $cantidad_grupo_adscripto = $_POST['cantidad_grupo_adscripto'] ?? null;
    $horario_entrada_adscripto = $_POST['horario_entrada_adscripto'] ?? null;
    $horario_entrada_adscripto .= ":00";
    $horario_salida_adscripto = $_POST['horario_salida_adscripto'] ?? null;
    $horario_salida_adscripto .= ":00";
    $caracter_cargo_adscripto = $_POST['caracter_cargo_adscripto'] ?? null;

    $sql_adscripto = "INSERT INTO adscripto (cantidad_grupos_asignados, horario_entrada_adscripto, horario_salida_adscripto, caracter_cargo_adscripto, id_usuario) 
                      VALUES ($cantidad_grupo_adscripto, '$horario_entrada_adscripto', '$horario_salida_adscripto', '$caracter_cargo_adscripto', $idUsuario)";
    mysqli_query($conn, $sql_adscripto);

} elseif ($cargo_usuario === "Secretario") {
    $horario_entrada_secretario = $_POST['horario_entrada_secretario'] ?? null;
    $horario_entrada_secretario .= ":00";
    $horario_salida_secretario = $_POST['horario_salida_secretario'] ?? null;
    $horario_salida_secretario .= ":00";
    $grado_secretario = $_POST['grado_secretario'] ?? null;
    
    $sql_secretario = "INSERT INTO secretario (horario_entrada_secretario, horario_salida_secretario, grado_secretario, id_usuario) 
                       VALUES ('$horario_entrada_secretario','$horario_salida_secretario', '$grado_secretario', $idUsuario)";
    mysqli_query($conn, $sql_secretario);
}

if($query){
    // Redirige de nuevo al listado
    header("Location: secretario-usuario.php");
    exit;
} else {
    echo "Error en la inserción: " . mysqli_error($conn);
}


?>