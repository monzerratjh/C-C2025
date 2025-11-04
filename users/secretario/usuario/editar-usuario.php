<?php
include(dirname(__FILE__).'./encabezado.php');
include('..\..\..\conexion.php');
$conn = conectar_bd();

// Supongamos que los valores vienen de un formulario POST
$id_usuario = $_POST['id_usuario'];
$ci_usuario = $_POST['ci_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$gmail_usuario = $_POST['gmail_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];
$cargo_usuario = $_POST['cargo_usuario'];
$contrasenia_usuario = $_POST['contrasenia_usuario']; //if '' ? null : hash

$hashed_password = password_hash($contrasenia_usuario, PASSWORD_BCRYPT);

if(empty($contrasenia_usuario)) {
    // Si está vacía, mantener la contraseña actual
    $sql_actual = "SELECT contrasenia_usuario FROM usuario WHERE id_usuario = ?";
    $stmt_actual = $conn->prepare($sql_actual);
    $stmt_actual->bind_param("i", $id_usuario);
    $stmt_actual->execute();
    $resultado = $stmt_actual->get_result();
    $usuario_actual = $resultado->fetch_assoc();
    $hashed_password = $usuario_actual['contrasenia_usuario'];
} else {
    // Si NO está vacía, hashear la nueva contraseña
    $hashed_password = password_hash($contrasenia_usuario, PASSWORD_BCRYPT);
}

// Traemos el cargo anterior antes de actualizar
$sql_prev = "SELECT cargo_usuario FROM usuario WHERE id_usuario = ?";
$stmt_prev = $conn->prepare($sql_prev);
$stmt_prev->bind_param("i", $id_usuario);
$stmt_prev->execute();
$result_prev = $stmt_prev->get_result();
$prev = $result_prev->fetch_assoc();
$cargo_anterior = $prev['cargo_usuario'] ?? null;
$stmt_prev->close();

// Verificar que el teléfono no se repita en otro usuario
$sql_tel = "SELECT id_usuario FROM usuario WHERE telefono_usuario = ? AND id_usuario <> ?";
$stmt_tel = $conn->prepare($sql_tel);
$stmt_tel->bind_param("si", $telefono_usuario, $id_usuario);
$stmt_tel->execute();
$result_tel = $stmt_tel->get_result();

if ($result_tel->num_rows > 0) {
    // Teléfono ya lo tiene otro usuario
    header("Location: ./secretario-usuario.php?error=TelefonoYaExistente");
    exit;
}

$stmt_tel->close();


//Actualizamos la tabla usuario
$stmt = $conn->prepare("UPDATE usuario
    SET ci_usuario = ?,
        nombre_usuario = ?,
        apellido_usuario = ?,
        gmail_usuario = ?,
        telefono_usuario = ?,
        cargo_usuario = ?,
        contrasenia_usuario = ?
    WHERE id_usuario = ?");
$stmt->bind_param("issssssi", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $hashed_password, $id_usuario);
$stmt->execute();
$stmt->close();

//Si cambió de cargo, eliminamos de la tabla anterior e insertamos en la nueva
if ($cargo_anterior !== $cargo_usuario) {
    // Eliminamos de la tabla anterior
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

    // Insertamos en la nueva tabla
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

// Redirigir
header("Location: ./secretario-usuario.php");
exit;
// Función de validación (similar a la de agregar-usuario.php)
function validarEdicion($ci, $nombre, $apellido, $correo, $tel, $cargo, $pass, $conn) {

    if(empty($ci) || empty($nombre) || empty($apellido) || empty($correo) || empty($tel) || empty($cargo)) {
        header("Location: ./secretario-usuario.php?error=CamposVacios");
        exit;
    }

    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{3,15}$/", $nombre)) {
        header("Location: ./secretario-usuario.php?error=NombreInvalido");
        exit;
    }

    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{3,15}$/", $apellido)) {
        header("Location: ./secretario-usuario.php?error=ApellidoInvalido");
        exit;
    }

    if (!preg_match("/^[0-9]{8}$/", $ci)) {
        header("Location: ./secretario-usuario.php?error=CiInvalida");
        exit;
    }

    if (!preg_match("/^[0-9]{9}$/", $tel)) {
        header("Location: ./secretario-usuario.php?error=TelefonoInvalido");
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header("Location: ./secretario-usuario.php?error=EmailInvalido");
        exit;
    }

    if ($pass !== "" && !preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}$/", $pass)) {
        header("Location: ./secretario-usuario.php?error=ContraseniaInvalida");
        exit;
    }
}
?>
?>
