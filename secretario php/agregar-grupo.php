<?php
include('..\conexion.php');

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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Grupos secretario</title>

  <!-- Bootstrap CSS + Iconos + letras-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <a href="../index.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cerrar Sesión</a>
 
       <a href="secretario-usuario.php" class="nav-opciones">Usuarios</a>
        <a href="secretario-horario.php" class="nav-opciones">Horarios</a>
        <a href="secretario-grupo.php" class="fw-semibold seleccionado mb-2">Grupos</a>
        </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../index.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cerrar Sesión</a>
        </div>
        </div>

        <a href="secretario-usuario.php" class="nav-opciones">Usuarios</a>
        <a href="secretario-horario.php" class="nav-opciones">Horarios</a>
        <a href="secretario-grupo.php" class="fw-semibold seleccionado mb-2">Grupos</a>
       </div>

    <!-- Contenido principal-->
    <div class="col-md-9 horarios-estudiantes"> <!-- Boostrap contendio al lado del menu -->
      <img src="../img/logo.png" alt="Logo" class="logo">

      <div class="acordion-total">
  <div class="acordion">

    <div class="bloque-agregar">
    <button class="etiqueta">Grupos</button>
    <!-- Botón para abrir el modal de creación de nuevo grupo -->
    <button class="agregar" 
            data-bs-toggle="modal" 
            data-bs-target="#modalGrupo" 
            onclick="document.getElementById('accion').value='insertar';">
        +
    </button>
  </div>

  
  <!-- Mensaje de éxito opcional al realizar acciones -->
<?php if(isset($_GET['message'])) 
    echo "<div class='alert alert-success'>".$_GET['message']."</div>"; 
?>



<!-- Tabla que muestra todos los grupos -->
<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Nombre del grupo</th>
<th>Orientación</th>
<th>Turno</th>
<th>Cantidad alumnos</th>
<th>Adscripto</th>
<th></th> <!-- Para botones de acción -->
</tr>

<!-- Recorremos los resultados de la consulta y llenamos la tabla -->
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id_grupo']; ?></td>
<td><?php echo $row['nombre_grupo']; ?></td>
<td><?php echo $row['orientacion_grupo']; ?></td>
<td><?php echo $row['turno_grupo']; ?></td>
<td><?php echo $row['cantidad_alumno_grupo']; ?></td>
<td><?php echo $row['nombre_usuario']." ".$row['apellido_usuario']; ?></td>
<td>
    <!-- Botón para editar: abre el mismo modal y carga los datos existentes -->
    <button class="btn btn-sm btn-warning" 
            data-bs-toggle="modal" 
            data-bs-target="#modalGrupo"
            onclick="cargarEditar(
                '<?php echo $row['id_grupo']; ?>',
                '<?php echo $row['orientacion_grupo']; ?>',
                '<?php echo $row['turno_grupo']; ?>',
                '<?php echo $row['nombre_grupo']; ?>',
                '<?php echo $row['cantidad_alumno_grupo']; ?>'
            )">
        Editar
    </button>

    <!-- Formulario para eliminar un grupo -->
    <form id="formEliminar<?php echo $row['id_grupo'];?>" 
          method="POST" 
          action="grupo_accion.php" 
          style="display:inline;">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id_grupo" value="<?php echo $row['id_grupo']; ?>">
        <button type="button" class="btn btn-sm btn-danger" 
                onclick="confirmarEliminar(<?php echo $row['id_grupo']; ?>)">
            Eliminar
        </button>
    </form>
</td>
</tr>
<?php } ?>
</table>

<!-- Modal para insertar o editar un grupo -->
<div class="modal fade" id="modalGrupo" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Grupo</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST" action="grupo_accion.php">
<div class="modal-body">

<!-- Campos ocultos para saber si es inserción o edición -->
<input type="hidden" id="accion" name="accion">
<input type="hidden" id="id_grupo" name="id_grupo">

<!-- Nombre del grupo -->
<div class="mb-3">
<label>Nombre del grupo</label>
<input type="text" class="form-control" id="nombre" name="nombre" required>
</div>

<!-- Orientación del grupo -->
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

<!-- Turno del grupo -->
<div class="mb-3">
  <label>Turno</label>
  <select class="form-control" id="turno" name="turno" required>
      <option value="">Seleccione...</option>
      <option value="Matutino">Matutino</option>
      <option value="Vespertino">Vespertino</option>
      <option value="Nocturno">Nocturno</option>
  </select>
</div>

<!-- Cantidad de alumnos -->
<div class="mb-3">
<label>Cantidad de alumnos</label>
<input type="number" class="form-control" id="cantidad" name="cantidad" required>
</div>

<!-- Adscripto: lista desplegable cargada desde la DB -->
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

<!-- Campo oculto para el id del secretario (fijo para pruebas) -->
<div class="mb-3">
<input type="hidden" name="id_secretario" value="1"> 
<!-- Para producción se usaría la sesión:
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

<!-- Script externo para funciones JS (editar y eliminar) -->
<script src="grupo.js"></script>

<!-- Bootstrap JS -->
<script src="desplegarCaracteristicas.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
