<?php 
include('../conexion.php');
include('../encabezado.php');
$conn = conectar_bd();

$sql = "SELECT * FROM usuario";
$query = mysqli_query($conn, $sql); //mysqli_query FUNCIÓN de php para EJECUTAR SQL
/*Esta variable llamada query lo que hace es contener info. de la conección (si está conectada o no a la BD) y a la CONSULTA que se necesita hacerl.*/
$message = "";
?>

<!DOCTYPE html>
<head>
    <title>Ingreso de Horario</title>
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
                <option value="cargo_Docente_usuario">Docente</option>
                <option value="cargo_Adscripto_usuario">Adscripto</option>
                <option value="cargo_Secretario_usuario">Secretario</option>
            </select>
            <?php
            
            ?>
            <input type="password" name="contrasenia_usuario" placeholder="Contraseña" id="contrasenia_usuario" >
            <input type="submit" value="agregacion_Usuario">
        </form>
    </div>

    <script src=""> </script>
</body>
</html>