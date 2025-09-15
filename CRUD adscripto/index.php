<?php
include("connection.php");
$con = connection();

$sql = "SELECT * FROM usuario";
$query = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Usuarios CRUD</title>
</head>

<body>
    <div class="users-form">
        <h1>Crear usuario</h1>
        <form action="insert_user.php" method="POST">
            <input type="text" name="nombre_usuario" placeholder="Nombre" required>
            <input type="text" name="apellido_usuario" placeholder="Apellido" required>
            <input type="email" name="gmail_usuario" placeholder="Correo electrónico" required>
            <input type="text" name="telefono_usuario" placeholder="Teléfono" required>

            <input type="submit" value="Agregar">
        </form>
    </div>

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
                        <td><a href="update.php?id_usuario=<?= $row['id_usuario'] ?>" class="users-table--edit">Editar</a></td>
                        <td><a href="delete_user.php?id_usuario=<?= $row['id_usuario'] ?>" class="users-table--delete">Eliminar</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
