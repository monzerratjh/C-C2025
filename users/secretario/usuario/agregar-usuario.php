<?php
session_start();
include('./../../../conexion.php');
$conn = conectar_bd();

// -----------------------------------------------------------------------------
// Captura de datos desde POST
// -----------------------------------------------------------------------------
$ci_usuario = trim($_POST['ci_usuario'] ?? '');
$nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
$apellido_usuario = trim($_POST['apellido_usuario'] ?? '');
$gmail_usuario = trim($_POST['gmail_usuario'] ?? '');
$telefono_usuario = trim($_POST['telefono_usuario'] ?? '');
$cargo_usuario = trim($_POST['cargo_usuario'] ?? '');
$contrasenia_usuario = trim($_POST['contrasenia_usuario'] ?? '');
$contrasenia_hash = password_hash($contrasenia_usuario, PASSWORD_DEFAULT);

// Guardar temporalmente los datos en sesión por si hay errores
$_SESSION['old'] = $_POST;

// -----------------------------------------------------------------------------
// Validaciones principales
// -----------------------------------------------------------------------------
validaciones($conn, $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $contrasenia_usuario, $cargo_usuario);

// -----------------------------------------------------------------------------
// Inserción de usuario
// -----------------------------------------------------------------------------
$sql_insert_usuario = "INSERT INTO usuario (ci_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, contrasenia_usuario)
                       VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql_insert_usuario);
$stmt->bind_param("sssssss", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $contrasenia_hash);

if ($stmt->execute()) {
    $id_usuario = $conn->insert_id;

    // Insertar según el cargo seleccionado
    if ($cargo_usuario === 'Secretario') {
        $conn->query("INSERT INTO secretario (id_usuario) VALUES ($id_usuario)");
    } elseif ($cargo_usuario === 'Docente') {
        $conn->query("INSERT INTO docente (id_usuario) VALUES ($id_usuario)");
    } elseif ($cargo_usuario === 'Adscripto') {
        $conn->query("INSERT INTO adscripto (id_usuario) VALUES ($id_usuario)");
    }

    // si exito -> eliminar los datos guardados temporalmente
    unset($_SESSION['old']);

    // Redirección con mensaje de éxito
    header("Location: ./secretario-usuario.php?msg=InsercionExitosa");
    exit;
} else {
    header("Location: ./secretario-usuario.php?error=CamposVacios&abrirModal=true");
    exit;
}

// -----------------------------------------------------------------------------
// FUNCIONES AUXILIARES
// -----------------------------------------------------------------------------
function validaciones($conn, $ci_usuario, $nombre_usuario, $apellido_usuario,
                     $gmail_usuario, $telefono_usuario, $contrasenia_usuario,
                     $cargo_usuario) {

    // ------------------------- CAMPOS VACÍOS -------------------------
    if (empty($ci_usuario) || empty($nombre_usuario) || empty($apellido_usuario) ||
        empty($gmail_usuario) || empty($telefono_usuario) || empty($cargo_usuario) ||
        empty($contrasenia_usuario)) {
        header("Location: ./secretario-usuario.php?error=CamposVacios&abrirModal=true");
        exit;
    }

    // ------------------------- CÉDULA -------------------------
    if (!preg_match("/^[0-9]{8}$/", $ci_usuario)) {
        header("Location: ./secretario-usuario.php?error=CiInvalida&abrirModal=true");
        exit;
    }

    // ------------------------- NOMBRE -------------------------
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/", $nombre_usuario)) {
        header("Location: ./secretario-usuario.php?error=NombreInvalido&abrirModal=true");
        exit;
    }

    // ------------------------- APELLIDO -------------------------
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/", $apellido_usuario)) {
        header("Location: ./secretario-usuario.php?error=ApellidoInvalido&abrirModal=true");
        exit;
    }

    // ------------------------- EMAIL -------------------------
    if (!filter_var($gmail_usuario, FILTER_VALIDATE_EMAIL)) {
        header("Location: ./secretario-usuario.php?error=EmailInvalido&abrirModal=true");
        exit;
    }

    // ------------------------- TELÉFONO -------------------------
    if (!preg_match("/^[0-9]{9}$/", $telefono_usuario)) {
        header("Location: ./secretario-usuario.php?error=TelefonoInvalido&abrirModal=true");
        exit;
    }

    // ------------------------- CARGO -------------------------
    $cargos_validos = ['Secretario', 'Docente', 'Adscripto'];
    if (!in_array($cargo_usuario, $cargos_validos)) {
        header("Location: ./secretario-usuario.php?error=CargoInvalido&abrirModal=true");
        exit;
    }

    // ------------------------- CONTRASEÑA -------------------------
    if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}$/", $contrasenia_usuario)) {
        header("Location: ./secretario-usuario.php?error=ContraseniaInvalida&abrirModal=true");
        exit;
    }

    // ------------------------- DUPLICADOS -------------------------
    $campoDuplicado = consultarDuplicados($conn, $ci_usuario, $gmail_usuario, $telefono_usuario);
    if ($campoDuplicado !== null) {
        header("Location: ./secretario-usuario.php?error=Duplicado&campo={$campoDuplicado}&abrirModal=true");
        exit;
    }

    return true;
}

// -----------------------------------------------------------------------------
// Verificación de duplicados
// -----------------------------------------------------------------------------
function consultarDuplicados($conn, $ci_usuario, $gmail_usuario, $telefono_usuario) {
    $sql = "SELECT ci_usuario, gmail_usuario, telefono_usuario
            FROM usuario
            WHERE ci_usuario = ? OR gmail_usuario = ? OR telefono_usuario = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $ci_usuario, $gmail_usuario, $telefono_usuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row) {
        if ($row['ci_usuario'] == $ci_usuario) return 'cedula';
        if ($row['gmail_usuario'] == $gmail_usuario) return 'email';
        if ($row['telefono_usuario'] == $telefono_usuario) return 'telefono';
    }
    return null;
}
?>