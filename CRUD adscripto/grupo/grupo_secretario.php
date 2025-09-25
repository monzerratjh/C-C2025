<?php
include("connection.php");   // Incluye la conexión a la base de datos
$conn = connection();

// Consulta para obtener todos los grupos con información del adscripto
$result = $conn->query("SELECT g.*, a.id_adscripto, a.id_usuario AS id_usuario_adscripto, u.nombre_usuario, u.apellido_usuario 
                        FROM grupo g
                        JOIN adscripto a ON g.id_adscripto = a.id_adscripto
                        JOIN usuario u ON a.id_usuario = u.id_usuario");
    
/*  
    g.*,    -- Todas las columnas de la tabla **grupo** (g es alias de grupo)

    a.id_adscripto,     -- Columna id_adscripto de la tabla **adscripto** (a es alias de adscripto)

    a.id_usuario AS id_usuario_adscripto,       -- Columna id_usuario de la tabla **adscripto**, pero renombrada a id_usuario_adscripto para diferenciarla de id_usuario de la tabla usuario

    u.nombre_usuario,       -- Columna nombre_usuario de la tabla **usuario** (u es alias de usuario)

    u.apellido_usuario      -- Columna apellido_usuario de la tabla **usuario** (u es alias de usuario)


    FROM grupo g                     -- g = **grupo**

    JOIN adscripto a                  -- a = **adscripto**

        ON g.id_adscripto = a.id_adscripto    -- Relación: grupo.id_adscripto con adscripto.id_adscripto

    JOIN usuario u                     -- u = **usuario**

        ON a.id_usuario = u.id_usuario        -- Relación: adscripto.id_usuario con usuario.id_usuario


Los alias (g, a, u) sirven para acortar los nombres de tabla y hacer más legible la consulta, especialmente cuando tenés varias tablas con columnas con nombres iguales (como id_adscripto o id_usuario).


- JOIN adscripto a ON g.id_adscripto = a.id_adscripto

Une cada grupo con su adscripto correspondiente.

- JOIN usuario u ON a.id_usuario = u.id_usuario

Une cada adscripto con el usuario asociado para obtener nombre y apellido.

*/

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Grupos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4">
<h3>Grupos</h3>
<?php if(isset($_GET['message'])) echo "<div class='alert alert-success'>".$_GET['message']."</div>"; ?>

<!-- Botón para modal de nuevo grupo -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalGrupo" onclick="document.getElementById('accion').value='insertar';">Nuevo Grupo</button>

<!-- Tabla -->
<table class="table table-bordered">
<tr>
<th>ID</th> <!-- ID SE VA DESP -->
<th>Nombre del grupo</th>
<th>Orientación</th>
<th>Turno</th>
<th>Cantidad alumnos</th>
<th>Adscripto</th>
<th></th>
</tr>
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id_grupo']; ?></td> <!-- ID SE VA DESP -->
<td><?php echo $row['nombre_grupo']; ?></td>
<td><?php echo $row['orientacion_grupo']; ?></td>
<td><?php echo $row['turno_grupo']; ?></td>
<td><?php echo $row['cantidad_alumno_grupo']; ?></td>
<td><?php echo $row['nombre_usuario']." ".$row['apellido_usuario']; ?></td>
<td>
    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalGrupo"
    onclick="cargarEditar('<?php echo $row['id_grupo']; ?>','<?php echo $row['orientacion_grupo']; ?>','<?php echo $row['turno_grupo']; ?>','<?php echo $row['nombre_grupo']; ?>','<?php echo $row['cantidad_alumno_grupo']; ?>')">Editar</button>

    <form id="formEliminar<?php echo $row['id_grupo'];?>" method="POST" action="grupo_accion.php" style="display:inline;">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id_grupo" value="<?php echo $row['id_grupo']; ?>">
        <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo $row['id_grupo']; ?>)">Eliminar</button>
    </form>
</td>
</tr>
<?php } ?>
</table>

<!-- Modal Insert/Edit -->
<div class="modal fade" id="modalGrupo" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Grupo</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<form method="POST" action="grupo_accion.php">
<div class="modal-body">
<input type="hidden" id="accion" name="accion">
<input type="hidden" id="id_grupo" name="id_grupo">

<div class="mb-3">
<label>Nombre del grupo</label>
<input type="text" class="form-control" id="nombre" name="nombre" required>
</div>

<div class="mb-3">
    <label for="orientacion">Orientación</label>
    <input type="text" name="orientacion" class="form-control"
        placeholder="Ingrese la orientación" list="orientaciones" id="orientacionInput" required />

    <datalist id="orientaciones">
        <option value="Tecnologías de la Información"></option>
        <option value="Tecnologías de la Información Bilingüe"></option>
        <option value="Finest IT y Redes"></option>
        <option value="Redes y Comunicaciones Ópticas"></option>
        <option value="Diseño Gráfico en Comunicación Visual"></option>
        <option value="Secretariado Bilingüe - Inglés"></option>
        <option value="Tecnólogo en Ciberseguridad"></option>
    </datalist>
</div>


<div class="mb-3">
  <label>Turno</label>
  <select class="form-control" id="turno" name="turno" required>
      <option value="">Seleccione...</option>
      <option value="Matutino">Matutino</option>
      <option value="Vespertino">Vespertino</option>
      <option value="Nocturno">Nocturno</option>
  </select>
</div>
<div class="mb-3">
<label>Cantidad de alumnos</label>
<input type="number" class="form-control" id="cantidad" name="cantidad" required>
</div>
<div class="mb-3">
<label>Adscripto</label>
<select class="form-control" name="id_adscripto" required>
<?php
$ads = $conn->query("SELECT a.id_adscripto, u.nombre_usuario, u.apellido_usuario FROM adscripto a JOIN usuario u ON a.id_usuario=u.id_usuario");
while($a = $ads->fetch_assoc()){
    echo "<option value='".$a['id_adscripto']."'>".$a['nombre_usuario']." ".$a['apellido_usuario']."</option>";
}
?>
</select>
</div>

<div class="mb-3">
<input type="hidden" name="id_secretario" value="1"> <!-- fijo para pruebas -->
<!--

<input type="hidden" name="id_secretario" value="<?php echo $_SESSION['id_secretario']; ?>">

-->
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
<button type="submit" class="btn btn-primary">Guardar</button>
</div>
</form>
</div>
</div>
</div>

<script src="grupo.js"></script>

</body>
</html>
