<?php 
include('conexion_BD.php');

$conn = conectar_bd();

$sql = "SELECT * FROM usuario";
$query = mysqli_query($conn, $sql); //mysqli_query FUNCIÓN de php para EJECUTAR SQL
/*Esta variable llamada query lo que hace es contener info. de la conección (si está conectada o no a la BD) y a la CONSULTA que se necesita hacerl.*/
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/CRUD adscripto/style.css">
</head>

<body>
    <div class="users-form">
        <h1>Ingreso de Usuarios.</h1>

        <?php if($message): ?>
         <p style="color:red; font-weight:bold;"><?= $message ?></p>
        <?php endif; ?>

     <form action='./insert_user_secretario.php' method="POST">
            <input type="number" name="ci_usuario" placeholder="CI" required>
            <input type="text" name="nombre_usuario" placeholder="Nombres" required>
            <input type="text" name="apellido_usuario" placeholder="Apellidos" required>
            <input type="gmail" name="gmail_usuario" placeholder="Gmail" required>
            <input type="number" name="telefono_usuario" placeholder="Telefono" required>
            <!--<select name="cargo_usuario" id="usuarioTipo" placeholder="Cargo" required>
            <option value="cargo_Docente_usuario">Docente</option>
            <option value="cargo_Adscripto_usuario">Adscripto</option>
            <option value="cargo_Secretario_usuario">Secretario</option>
            </select>-->
            <input type="password" name="contrasenia_usuario" placeholder="Contraseña" required>
            <input type="submit" value="agregacion_Usuario">
        </form>
    </div>
    <div>
        <h2>Usuarios creados:</h2>
        <table> 
            <thead> <!-- HEAD de una TABLA en HTML-->
                <tr> <!-- FILA en la tabla-->
                  <th>I.D</th>  <!-- Define una CELDA de ENCABEZADO en una tabla. Estas celdas suelen contener títulos o descripciones que identifican el contenido de las filas o columnas de la tabla -->
                  <th>C.I</th>
                  <th>Nombre(s)</th>
                  <th>Apellido(s)</th>
                  <!--<th>Cargo</th>-->
                  <th>Gmail</th>
                  <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = mysqli_fetch_array($query)):
                ?>
                <tr>
                    <th><?= $row['id_usuario']  ?></th>
                    <th><?= $row['ci_usuario']  ?></th>
                    <th><?= $row['nombre_usuario']  ?></th>
                    <th><?= $row['apellido_usuario']  ?></th>
                    <th><?= $row['gmail_usuario']  ?></th>
                    <th><?= $row['telefono_usuario']  ?></th>
                    <th><!--CARGO--></th>
                
                    <th><a href="./update_user_secretario.php?id_usuario=<?= $row['id_usuario'] ?>">Editar Usuario</a></th>
                    <th><a href="./delete_user_secretario.php?id_usuario=<?= $row['id_usuario']  ?>">Eliminar Usuario</a></th>
                </tr>
                <?php 
                endwhile;
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>