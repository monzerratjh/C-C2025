<?php 
include('..\conexion.php');
$conn = conectar_bd();

$sql = "SELECT * FROM usuario";
$query = mysqli_query($conn, $sql); //mysqli_query FUNCIÓN de php para EJECUTAR SQL
/*Esta variable llamada query lo que hace es contener info. de la conección (si está conectada o no a la BD) y a la CONSULTA que se necesita hacerl.*/
$message = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Usuarios</title>
</head>

<body>
    <div class="users-form">
        <h1>Ingreso de Usuarios.</h1>

        <?php if($message): ?>
         <p style="color:red; font-weight:bold;"><?= $message ?></p>
        <?php endif; ?>

     <form action='./insert_user_secretario.php' method="POST">
            <input type="number" name="ci_usuario" placeholder="CI" id="ci_usuario" >
            <input type="text" name="nombre_usuario" placeholder="Nombres" id="nombre_usuario" >
            <input type="text" name="apellido_usuario" placeholder="Apellidos" id="apellido_usuario" >
            <input type="gmail" name="gmail_usuario" placeholder="Gmail" id="gmail_usuario">
            <input type="number" name="telefono_usuario" placeholder="Telefono" id="telefono_usuario">
            <select name="cargo_usuario" id="usuarioTipo" placeholder="Cargo" required>
                <option value="">Seleccionar</option>
                <option value="Docente">Docente</option>
                <option value="Adscripto">Adscripto</option>
                <option value="Secretario">Secretario</option>
            </select>
            <input type="password" name="contrasenia_usuario" placeholder="Contraseña" id="contrasenia_usuario" >
            <div id="extra-inputs"></div>
            <input type="submit" value="Agregar Usuario">
        </form>
    </div>

    <script src="./validation.js"></script>
    <script src="./extra-inputs.js"></script>
</body>
</html>