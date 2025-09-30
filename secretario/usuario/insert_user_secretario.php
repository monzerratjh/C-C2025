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

// Hashear la contraseña
$hashed_password = password_hash($contrasenia_usuario, PASSWORD_BCRYPT);

$sql = "INSERT INTO usuario 
      (ci_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, contrasenia_usuario) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    die("Error en prepare: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "sssssss", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $hashed_password);
$success = mysqli_stmt_execute($stmt);
$idUsuario = $conn->insert_id;


if ($cargo_usuario === "Docente") {
    $grado = $_POST['grado_docente_usuario'] ?? null;
    $sql_docente = "INSERT INTO docente (grado_docente, id_usuario) 
                    VALUES ($grado, $idUsuario);";
    $stmt_doc = mysqli_prepare($conn, $sql_docente);
        mysqli_stmt_bind_param($stmt_doc, "is", $id_usuario, $grado);
        mysqli_stmt_execute($stmt_doc);

} elseif ($cargo_usuario === "Adscripto") {
    $cantidad_grupo_adscripto = $_POST['cantidad_grupo_adscripto'] ?? null;
    $horario_entrada_adscripto = $_POST['horario_entrada_adscripto'] ?? null;
    $horario_entrada_adscripto .= ":00";
    $horario_salida_adscripto = $_POST['horario_salida_adscripto'] ?? null;
    $horario_salida_adscripto .= ":00";
    $caracter_cargo_adscripto = $_POST['caracter_cargo_adscripto'] ?? null;

    $sql_adscripto = "INSERT INTO adscripto (cantidad_grupos_asignados, horario_entrada_adscripto, horario_salida_adscripto, caracter_cargo_adscripto, id_usuario) 
                      VALUES (?, ?, ?, ?, ?)";
   $stmt_ads = mysqli_prepare($conn, $sql_adscripto);
   
    if (!$stmt_ads) {
        die("Error prepare adscripto: " . mysqli_error($conn));
    }
        mysqli_stmt_bind_param($stmt_ads, "isssi", $cantidad_grupo_adscripto, $horario_entrada_adscripto, $horario_salida_adscripto, $caracter_cargo_adscripto, $idUsuario);
        mysqli_stmt_execute($stmt_ads);

} elseif ($cargo_usuario === "Secretario") {
    $horario_entrada_secretario = $_POST['horario_entrada_secretario'] ?? null;
    $horario_entrada_secretario .= ":00";
    $horario_salida_secretario = $_POST['horario_salida_secretario'] ?? null;
    $horario_salida_secretario .= ":00";
    $grado_secretario = $_POST['grado_secretario'] ?? null;
    
    $sql_secretario = "INSERT INTO secretario (horario_entrada_secretario, horario_salida_secretario, grado_secretario, id_usuario) 
                       VALUES ('$horario_entrada_secretario','$horario_salida_secretario', '$grado_secretario', $idUsuario)";
    $stmt_sec = mysqli_prepare($conn, $sql_secretario);
        mysqli_stmt_bind_param($stmt_sec, "iss", $id_usuario, $horario_secretario, $cargo_secretario);
        mysqli_stmt_execute($stmt_sec);
}

if($success){
    // Redirige de nuevo al listado
    header("Location: secretario-usuario.php");
    exit;
} else {
    echo "Error en la inserción: " . mysqli_error($conn);
}


?>