<?php
include("connection.php");
$con = connection();

$message = ""; // Mensaje de error o éxito
$success = false; // Inserción correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Escapar entradas para seguridad
    $nombre   = mysqli_real_escape_string($con, $_POST['nombre_usuario']);
    $apellido = mysqli_real_escape_string($con, $_POST['apellido_usuario']);
    $gmail    = mysqli_real_escape_string($con, $_POST['gmail_usuario']);
    $telefono = mysqli_real_escape_string($con, $_POST['telefono_usuario']);
    $cedula   = mysqli_real_escape_string($con, $_POST['ci_usuario']);
    $password = mysqli_real_escape_string($con, $_POST['contraseña_usuario']); // NUEVO campo

    // Validaciones
    if (!preg_match('/^\d{8}$/', $cedula)) {
        $message = "La cédula debe tener exactamente 8 números.";
    } elseif (!preg_match('/^\d{9}$/', $telefono)) {
        $message = "El teléfono debe tener exactamente 9 números.";
    } elseif (strlen($password) < 6) {
        $message = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Verificar si usuario ya existe
        $sql_check = "SELECT * FROM usuario WHERE ci_usuario='$cedula' OR gmail_usuario='$gmail'";
        $query_check = mysqli_query($con, $sql_check);

        if (mysqli_num_rows($query_check) > 0) {
            $message = "El usuario con esta cédula o correo ya existe en la base de datos.";
        } else {
            // Hashear la contraseña
            $hash_password = password_hash($password, PASSWORD_BCRYPT);

            // Insertar usuario
            $sql_insert = "INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, ci_usuario, contraseña_usuario) 
                           VALUES ('$nombre', '$apellido', '$gmail', '$telefono', '$cedula', '$hash_password')";
            $query_insert = mysqli_query($con, $sql_insert);

            if ($query_insert) {
                $message = "Usuario creado correctamente.";
                $success = true;
            } else {
                $message = "Error al insertar: " . mysqli_error($con);
            }
        }
    }
}

// Traer todos los usuarios
$sql = "SELECT id_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, ci_usuario FROM usuario";
$query = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="style.css" rel="stylesheet">
<title>Usuarios CRUD</title>
</head>
<body>

<div class="users-form">
    <h1>Crear usuario</h1>

    <?php if($message): ?>
        <p style="color:red; font-weight:bold;"><?= $message ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="text" name="nombre_usuario" placeholder="Nombre" 
               value="<?= (!$success && isset($_POST['nombre_usuario'])) ? $_POST['nombre_usuario'] : '' ?>" required>

        <input type="text" name="apellido_usuario" placeholder="Apellido" 
               value="<?= (!$success && isset($_POST['apellido_usuario'])) ? $_POST['apellido_usuario'] : '' ?>" required>

        <input type="email" name="gmail_usuario" placeholder="Correo electrónico" 
               value="<?= (!$success && isset($_POST['gmail_usuario'])) ? $_POST['gmail_usuario'] : '' ?>" required>

        <input type="text" name="telefono_usuario" placeholder="Teléfono" 
               value="<?= (!$success && isset($_POST['telefono_usuario'])) ? $_POST['telefono_usuario'] : '' ?>" required>

        <input type="text" name="ci_usuario" placeholder="Cédula" 
               value="<?= (!$success && isset($_POST['ci_usuario'])) ? $_POST['ci_usuario'] : '' ?>" required>

        <input type="password" name="contraseña_usuario" placeholder="Contraseña" required>

        <input type="submit" value="Agregar">
    </form>
</div>

<!-- Tabla de usuarios -->
<div class="users-table">
    <h2>Usuarios registrados</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Cédula</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($query)): ?>
                <tr>
                    <td><?= $row['id_usuario'] ?></td>
                    <td><?= $row['nombre_usuario'] ?></td>
                    <td><?= $row['apellido_usuario'] ?></td>
                    <td><?= $row['gmail_usuario'] ?></td>
                    <td><?= $row['telefono_usuario'] ?></td>
                    <td><?= $row['ci_usuario'] ?></td>
                    <td><a href="update.php?id_usuario=<?= $row['id_usuario'] ?>">Editar</a></td>
                    <td><a href="delete_user.php?id_usuario=<?= $row['id_usuario'] ?>">Eliminar</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
