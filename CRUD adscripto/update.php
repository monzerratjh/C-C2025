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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
    <title>Editar usuario</title>
</head>
<body>
    <div class="users-form">
        <h1>Editar usuario</h1>
        <form action="" method="POST">
            <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">

            <input type="text" name="nombre_usuario" placeholder="Nombre" value="<?= $row['nombre_usuario'] ?>" required>

            <input type="text" name="apellido_usuario" placeholder="Apellido" value="<?= $row['apellido_usuario'] ?>" required>
            
            <input type="email" name="gmail_usuario" placeholder="Correo electrónico" value="<?= $row['gmail_usuario'] ?>" required>

            <input type="text" name="telefono_usuario" placeholder="Teléfono" value="<?= $row['telefono_usuario'] ?>" required
                pattern="\d{9}" title="Debe ingresar exactamente 9 números">

            <input type="text" name="ci_usuario" placeholder="Cédula" value="<?= $row['ci_usuario'] ?>" required
                   pattern="\d{8}" title="Debe ingresar exactamente 8 números">

            <input type="submit" value="Actualizar">
        </form>
    </div>
</body>
</html>
