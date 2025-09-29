<?php
include('C:\xampp\htdocs\C-C2025\connection.php');

$conn = connection();

$id_usuario = null;
$ci_usuario = $_POST['ci_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$gmail_usuario = $_POST['gmail_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];
$cargo_usuario = $_POST['cargo_usuario'];
$contrasenia_usuario = $_POST['contrasenia_usuario'];

$sql = "INSERT INTO usuario 
      (id_usuario, ci_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, contrasenia_usuario) 
    VALUES('$id_usuario', '$ci_usuario', '$nombre_usuario', '$apellido_usuario', '$gmail_usuario', '$telefono_usuario','$cargo_usuario','$contrasenia_usuario')";
$query = mysqli_query($conn, $sql);

if ($cargo_usuario === "Docente") {
    $grado = $_POST['grado_docente_usuario'] ?? null;
    $sql_docente = "INSERT INTO docente (id_usuario, grado_docente_usuario) 
                    VALUES ('$id_usuario', '$grado')";
    mysqli_query($conn, $sql_docente);

} elseif ($cargo_usuario === "Adscripto") {
    $cantidad_grupo_adscripto = $_POST['cantidad_grupo_adscripto'] ?? null;
    $horario_adscripto = $_POST['horario_adscripto_usuario'] ?? null;
    $caracter_cargo_adscripto = $_POST['caracter_cargo_adscripto'] ?? null;

    $sql_adscripto = "INSERT INTO adscripto (id_usuario, cantidad_grupo_adscripto, horario_adscripto_usuario, caracter_cargo_adscripto) 
                      VALUES ('$id_usuario',$cantidad_grupo_adscripto', '$horario_adscripto', '$caracter_cargo_adscripto')";
    mysqli_query($conn, $sql_adscripto);

} elseif ($cargo_usuario === "Secretario") {
    $horario_secretario = $_POST['horario_secretario_usuario'] ?? null;
    $cargo_secretario = $_POST['cargo_secretario_usuario'] ?? null;

    $sql_secretario = "INSERT INTO secretario (id_usuario, horario_secretario_usuario, cargo_secretario_usuario) 
                       VALUES (''$id_usuario',$horario_secretario', '$cargo_secretario')";
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