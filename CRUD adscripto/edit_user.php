<?php

include("connection.php");
$con = connection();

// Recibir datos del formulario
$id = $_POST["id_usuario"];
$nombre = $_POST['nombre_usuario'];
$apellido = $_POST['apellido_usuario'];
$gmail = $_POST['gmail_usuario'];
$telefono = $_POST['telefono_usuario'];
$cedula = $_POST['ci_usuario']; 
$pass = $_POST['password_usuario'];

// Si el usuario escribió contraseña, generar hash
if (!empty($pass)) {
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql_pass = ", password_usuario='$pass_hash'";
} else {
    $sql_pass = ""; // No se cambia la contraseña
}

// Sentencia SQL
$sql = "UPDATE usuario 
        SET nombre_usuario='$nombre', 
            apellido_usuario='$apellido', 
            gmail_usuario='$gmail', 
            telefono_usuario='$telefono',
            ci_usuario='$cedula'
            $sql_pass,
            password_usuario='$pass_hash'
        WHERE id_usuario='$id'";

$query = mysqli_query($con, $sql);

// Redirección si todo va bien
if($query){
    header("Location: index.php");
    echo ("Actualizado correctamente");
    exit();
} else {
    echo "Error al actualizar: " . mysqli_error($con);
}

?>

<?php
include("connection.php");
$con = connection();

// Procesar el POST si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_usuario'], $_POST['nombre_usuario'], $_POST['apellido_usuario'], $_POST['gmail_usuario'], $_POST['telefono_usuario'], $_POST['ci_usuario'])) {

        $id       = $_POST['id_usuario'];
        $nombre   = $_POST['nombre_usuario'];
        $apellido = $_POST['apellido_usuario'];
        $gmail    = $_POST['gmail_usuario'];
        $telefono = $_POST['telefono_usuario']; 
        $cedula   = $_POST['ci_usuario'];

        // Validación: cédula 8 dígitos
        if (!preg_match('/^\d{8}$/', $cedula)) {
            die("La cédula debe tener exactamente 8 números.");
        }

        // Validación: teléfono 9 dígitos
        if (!preg_match('/^\d{9}$/', $telefono)) {
            die("El teléfono debe tener exactamente 9 números.");
        }

        // UPDATE en la tabla usuario
        $sql = "UPDATE usuario 
                SET nombre_usuario='$nombre', 
                    apellido_usuario='$apellido', 
                    gmail_usuario='$gmail', 
                    telefono_usuario='$telefono',
                    ci_usuario='$cedula'
                WHERE id_usuario='$id'";

        $query = mysqli_query($con, $sql);

        if ($query) {
            header("Location: index.php"); // redirige a la lista de usuarios
            exit();
        } else {
            echo "Error al actualizar: " . mysqli_error($con);
        }

    } else {
        echo "Faltan datos para actualizar.";
    }

} else {
    // Si no hay POST, traemos los datos de la BD
    if (!isset($_GET['id_usuario'])) {
        die("No se especificó el usuario.");
    }

    $id = $_GET['id_usuario'];
    $sql = "SELECT * FROM usuario WHERE id_usuario='$id'";
    $query = mysqli_query($con, $sql);
    if (!$row = mysqli_fetch_array($query)) {
        die("Usuario no encontrado.");
    }
}
?>