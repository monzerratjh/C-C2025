<?php
include('..\conexion.php');
$conn = conectar_bd();

$ci_usuario       = $_POST['ci_usuario'];
$nombre_usuario   = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$gmail_usuario    = $_POST['gmail_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];
$cargo_usuario    = $_POST['cargo_usuario'];
$contrasenia_usuario = $_POST['contrasenia_usuario'];

// Hashear la contraseña
$hashed_password = password_hash($contrasenia_usuario, PASSWORD_BCRYPT);

// Insertar en tabla usuario
$sql = "INSERT INTO usuario 
       (ci_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, contrasenia_usuario) 
       VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssss", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $hashed_password);
$success = mysqli_stmt_execute($stmt);

if ($success) {
    // Obtenemos el ID generado automáticamente
    $id_usuario = mysqli_insert_id($conn);

    // Insertar en tabla correspondiente según el cargo
    if ($cargo_usuario === "Docente") {
        $grado = $_POST['grado_docente_usuario'] ?? '';
        $sql_docente = "INSERT INTO docente (id_usuario, grado_docente_usuario) VALUES (?, ?)";
        $stmt_doc = mysqli_prepare($conn, $sql_docente);
        mysqli_stmt_bind_param($stmt_doc, "is", $id_usuario, $grado);
        mysqli_stmt_execute($stmt_doc);

    } elseif ($cargo_usuario === "Adscripto") {
        $cantidad_grupo_adscripto = $_POST['cantidad_grupo_adscripto'] ?? 0;
        $horario_adscripto = $_POST['horario_adscripto_usuario'] ?? '';
        $caracter_cargo_adscripto = $_POST['caracter_cargo_adscripto'] ?? '';

        $sql_adscripto = "INSERT INTO adscripto (id_usuario, cantidad_grupo_adscripto, horario_adscripto_usuario, caracter_cargo_adscripto) 
                          VALUES (?, ?, ?, ?)";
        $stmt_ads = mysqli_prepare($conn, $sql_adscripto);
        mysqli_stmt_bind_param($stmt_ads, "iiss", $id_usuario, $cantidad_grupo_adscripto, $horario_adscripto, $caracter_cargo_adscripto);
        mysqli_stmt_execute($stmt_ads);

    } elseif ($cargo_usuario === "Secretario") {
        $horario_secretario = $_POST['horario_secretario_usuario'] ?? '';
        $cargo_secretario   = $_POST['cargo_secretario_usuario'] ?? '';

        $sql_secretario = "INSERT INTO secretario (id_usuario, horario_secretario_usuario, cargo_secretario_usuario) 
                           VALUES (?, ?, ?)";
        $stmt_sec = mysqli_prepare($conn, $sql_secretario);
        mysqli_stmt_bind_param($stmt_sec, "iss", $id_usuario, $horario_secretario, $cargo_secretario);
        mysqli_stmt_execute($stmt_sec);
    }

    // Redirigir al listado
    header("Location: secretario-usuario.php");
    exit;
} else {
    echo "Error en la inserción: " . mysqli_error($conn);
}
?>
