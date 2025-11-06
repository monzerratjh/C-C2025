<?php
session_start();
include('./../../../conexion.php');
$conn = conectar_bd();
session_start();

// -----------------------------------------------------------------------------
// Capturar datos POST
// -----------------------------------------------------------------------------
$id_usuario = intval($_POST['id_usuario'] ?? 0);
$ci_usuario = trim($_POST['ci_usuario'] ?? '');
$nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
$apellido_usuario = trim($_POST['apellido_usuario'] ?? '');
$gmail_usuario = trim($_POST['gmail_usuario'] ?? '');
$telefono_usuario = trim($_POST['telefono_usuario'] ?? '');
$cargo_usuario = trim($_POST['cargo_usuario'] ?? '');
$contrasenia_usuario = trim($_POST['contrasenia_usuario'] ?? '');

// Guardar los valores en sesión temporalmente para repoblar si hay error
$_SESSION['old_edit'] = [
  'id_usuario' => $id_usuario,
  'ci_usuario' => $ci_usuario,
  'nombre_usuario' => $nombre_usuario,
  'apellido_usuario' => $apellido_usuario,
  'gmail_usuario' => $gmail_usuario,
  'telefono_usuario' => $telefono_usuario,
  'cargo_usuario' => $cargo_usuario,
  'contrasenia_usuario' => $contrasenia_usuario,
];

// -----------------------------------------------------------------------------
// Validaciones
// -----------------------------------------------------------------------------
if (empty($ci_usuario) || empty($nombre_usuario) || empty($apellido_usuario) ||
    empty($gmail_usuario) || empty($telefono_usuario) || empty($cargo_usuario)) {
    header("Location: ./secretario-usuario.php?error=CamposVacios&abrirModal=true&id_usuario=$id_usuario");
    exit;
}

if (!preg_match("/^[0-9]{8}$/", $ci_usuario)) {
    header("Location: ./secretario-usuario.php?error=CiInvalida&abrirModal=true&id_usuario=$id_usuario");
    exit;
}

if (!preg_match("/^[0-9]{9}$/", $telefono_usuario)) {
    header("Location: ./secretario-usuario.php?error=TelefonoInvalido&abrirModal=true&id_usuario=$id_usuario");
    exit;
}

if (!empty($contrasenia_usuario) &&
    !preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}$/", $contrasenia_usuario)) {
    header("Location: ./secretario-usuario.php?error=ContraseniaInvalida&abrirModal=true&id_usuario=$id_usuario");
    exit;
}

// -----------------------------------------------------------------------------
// Verificar duplicados (excluyendo el usuario actual)
// -----------------------------------------------------------------------------
$campoDuplicado = verificarDuplicados($conn, $ci_usuario, $gmail_usuario, $telefono_usuario, $id_usuario);
if ($campoDuplicado !== null) {
    header("Location: ./secretario-usuario.php?error=Duplicado&campo={$campoDuplicado}&abrirModal=true&id_usuario=$id_usuario");
    exit;
}

// -----------------------------------------------------------------------------
// Obtener contraseña actual si no se cambió
// -----------------------------------------------------------------------------
if (empty($contrasenia_usuario)) {
    $sql = "SELECT contrasenia_usuario FROM usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $fila = $res->fetch_assoc();
    $hashed_password = $fila['contrasenia_usuario'];
    $stmt->close();
} else {
    $hashed_password = password_hash($contrasenia_usuario, PASSWORD_DEFAULT);
}

// -----------------------------------------------------------------------------
// Obtener cargo anterior
// -----------------------------------------------------------------------------
$sql_prev = "SELECT cargo_usuario FROM usuario WHERE id_usuario = ?";
$stmt_prev = $conn->prepare($sql_prev);
$stmt_prev->bind_param("i", $id_usuario);
$stmt_prev->execute();
$res_prev = $stmt_prev->get_result();
$cargo_anterior = $res_prev->fetch_assoc()['cargo_usuario'] ?? null;
$stmt_prev->close();

// -----------------------------------------------------------------------------
// Actualizar usuario
// -----------------------------------------------------------------------------
$sql_update = "UPDATE usuario SET 
    ci_usuario = ?, 
    nombre_usuario = ?, 
    apellido_usuario = ?, 
    gmail_usuario = ?, 
    telefono_usuario = ?, 
    cargo_usuario = ?, 
    contrasenia_usuario = ?
    WHERE id_usuario = ?";

$stmt = $conn->prepare($sql_update);
$stmt->bind_param("sssssssi", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $hashed_password, $id_usuario);

if (!$stmt->execute()) {
    header("Location: ./secretario-usuario.php?error=ErrorSQL&abrirModal=true&id_usuario=$id_usuario");
    exit;
}
$stmt->close();

// -----------------------------------------------------------------------------
// Si cambió el cargo, actualizar tabla correspondiente
// -----------------------------------------------------------------------------
if ($cargo_anterior !== $cargo_usuario) {
    // Eliminar de tabla anterior
    switch ($cargo_anterior) {
        case "Docente":
            $conn->query("DELETE FROM docente WHERE id_usuario = $id_usuario");
            break;
        case "Adscripto":
            $conn->query("DELETE FROM adscripto WHERE id_usuario = $id_usuario");
            break;
        case "Secretario":
            $conn->query("DELETE FROM secretario WHERE id_usuario = $id_usuario");
            break;
    }

    // Insertar en nueva tabla
    switch ($cargo_usuario) {
        case "Docente":
            $conn->query("INSERT INTO docente (id_usuario) VALUES ($id_usuario)");
            break;
        case "Adscripto":
            $conn->query("INSERT INTO adscripto (id_usuario) VALUES ($id_usuario)");
            break;
        case "Secretario":
            $conn->query("INSERT INTO secretario (id_usuario) VALUES ($id_usuario)");
            break;
    }
}

// -----------------------------------------------------------------------------
// si exito -> eliminar los datos guardados temporalmente
// -----------------------------------------------------------------------------
unset($_SESSION['old_edit']);
header("Location: ./secretario-usuario.php?msg=EdicionExitosa");
exit;

// -----------------------------------------------------------------------------
// FUNCIONES AUXILIARES
// -----------------------------------------------------------------------------
function verificarDuplicados($conn, $ci_usuario, $gmail_usuario, $telefono_usuario, $id_usuario) {
    $sql = "SELECT ci_usuario, gmail_usuario, telefono_usuario 
            FROM usuario 
            WHERE (ci_usuario = ? OR gmail_usuario = ? OR telefono_usuario = ?)
              AND id_usuario <> ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $ci_usuario, $gmail_usuario, $telefono_usuario, $id_usuario);
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