<?php 
include('C:\xampp\htdocs\C-C2025\connection.php');

$conn = connection();

$id_usuario = $_GET['id_usuario'] ?? null; // Si no viene nada, queda null
if (!$id_usuario) {
    echo "No se proporcionó un ID de usuario.";
    exit;
}


$sql = "SELECT * FROM usuario WHERE id_usuario='$id_usuario'";
$query = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($query); // $row ahora tiene los datos del usuario
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de usuario.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <h1>Edit Users</h1>
    <form action='./edit_user.php' method="POST">
        <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>" >
        <input type="number" name="ci_usuario" placeholder="CI" value="<?= $row['ci_usuario']  ?>" id="ci_usuario">
        <input type="text" name="nombre_usuario" placeholder="Nombres" value="<?= $row['nombre_usuario']  ?>" id="nombre_usuario">
        <input type="text" name="apellido_usuario" placeholder="Apellidos" value="<?= $row['apellido_usuario']  ?>" id="apellido_usuario">
        <input type="email" name="gmail_usuario" placeholder="Gmail" value="<?= $row['gmail_usuario']  ?>" id="gmail_usuario">
        <input type="number" name="telefono_usuario" placeholder="Telefono" value="<?= $row['telefono_usuario']  ?>" id="telefono_usuario">
        <select name="cargo_usuario" id="usuarioTipo" value="<?= $row['cargo_usuario']  ?>">
            <option value="cargo_Docente_usuario">Docente</option>
            <option value="cargo_Adscripto_usuario">Adscripto</option>
            <option value="cargo_Secretario_usuario">Secretario</option>
        </select>
        <input type="password" name="contrasenia_usuario" placeholder="Contraseña" value="<?= $row['contrasenia_usuario']  ?>" id="contrasenia_usuario" >
        <input type="submit" value="Editar usuario">
    </form>

    <script src="./validation.js"> </script>
</body>
</html>