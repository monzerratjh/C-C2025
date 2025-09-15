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
    <title>Creación de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <form>
        <input type="number" name="usuario_ci" placeholder="CI" required>
        <input type="text" name="usuario_nombre" placeholder="Nombres" required>
        <input type="text" name="usuario_apellido" placeholder="Apellidos" required>
        <input type="gmail" name="usuario_gmail" placeholder="Gmail" required>
        <input type="number" name="usuario_telefono" placeholder="Telefono" required>
        <select name="tipo_Usuario" id="tipoUsuario" placeholder="Cargo" required>
            <option value="tipo_usuario_Docente">Docente</option>
            <option value="tipo_usuario_Adscripto">Adscripto</option>
            <option value="tipo_usuario_Secretario">Secretario</option>
        </select>
        <input type="submit" value="agregacion_Usuario">
    </form>

    <div>
        <h2>Usuarios creados:</h2>
        <table> 
            <thead> <!-- HEAD de una TABLA en HTML-->
                <tr> <!-- FILA en la tabla-->
                  <th>I.D</th>  <!-- Define una CELDA de ENCABEZADO en una tabla. Estas celdas suelen contener títulos o descripciones que identifican el contenido de las filas o columnas de la tabla -->
                  <th>C.I</th>
                  <th>Nombre(s)</th>
                  <th>Apellido(s)</th>
                  <th>Cargo</th>
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
                
                    <th><a>Editar Usuario</a></th>
                    <th><a>Eliminar Usuario</a></th>
                </tr>
                <?php 
                endwhile;
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>