<?php
include('..\..\conexion.php');
$conn = conectar_bd();

$id_usuario = $_POST['id_usuario'] ?? null;
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
        $sql_docente = "INSERT INTO docente (id_usuario)
                    VALUES (?);";
        $stmt_doc = mysqli_prepare($conn, $sql_docente);
        mysqli_stmt_bind_param($stmt_doc, "i", $idUsuario);
        mysqli_stmt_execute($stmt_doc);

    } elseif ($cargo_usuario === "Adscripto") {
        $sql_adscripto = "INSERT INTO adscripto (id_usuario)
                      VALUES (?)";
        $stmt_ads = mysqli_prepare($conn, $sql_adscripto);

        if (!$stmt_ads) {
            die("Error prepare adscripto: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_ads, "i", $idUsuario);
        mysqli_stmt_execute($stmt_ads);
    } elseif ($cargo_usuario === "Secretario") {
        $sql_secretario = "INSERT INTO secretario (id_usuario)
                       VALUES (?)";
        $stmt_sec = mysqli_prepare($conn, $sql_secretario);
        mysqli_stmt_bind_param($stmt_sec, "i", $idUsuario);
        mysqli_stmt_execute($stmt_sec);
    }

    if ($success) {
        // Redirige de nuevo al listado
        header("Location: ./secretario-usuario.php");
        exit;
    } else {
        echo "Error en la inserción: " . mysqli_error($conn);
    }
