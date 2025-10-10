<?php
include('../../../conexion.php');
//include('../../../encabezado.php');
$con = conectar_bd();

// Consulta SQL para obtener todos los grupos junto con el adscripto asociado y el nombre del usuario adscripto
$result = $con->query("
    SELECT grupo.id_grupo,
           grupo.nombre_grupo,
           grupo.orientacion_grupo,
           grupo.turno_grupo,
           grupo.cantidad_alumno_grupo,
           adscripto.id_adscripto,
           adscripto.id_usuario AS id_usuario_adscripto,
           usuario.nombre_usuario,
           usuario.apellido_usuario
    FROM grupo
    JOIN adscripto
        ON grupo.id_adscripto = adscripto.id_adscripto
    JOIN usuario
        ON adscripto.id_usuario = usuario.id_usuario
    ORDER BY grupo.nombre_grupo ASC
");

// Obtener nombre y apellido adscripto mediante id (usuario)
$adscriptoResult = $con->query("SELECT adscripto.id_adscripto, usuario.nombre_usuario, usuario.apellido_usuario 
                                                         FROM adscripto 
                                                         JOIN usuario ON adscripto.id_usuario=usuario.id_usuario");

// Obtener las opciones del ENUM "orientacion_grupo" directamente desde la BD
$ENUMresult = $con->query("SHOW COLUMNS FROM grupo LIKE 'orientacion_grupo'");
$rowENUM = $ENUMresult->fetch_assoc();

// Extraer los valores entre comillas simples del tipo ENUM
preg_match_all("/'([^']+)'/", $rowENUM['Type'], $coincidencias);
$orientaciones = $coincidencias[1]; /* Array con todas las orientaciones válidas

 * preg_match_all -> match=coincidencia
 
$coincidencias[0] = ["'Tecnologías de la Información'", "'Diseño Gráfico'", "'Secretariado'"];
$coincidencias[1] = ["Tecnologías de la Información", "Diseño Gráfico", "Secretariado"];


fetch_assoc() funcion q toma una fila del resultado de una consulta SQL y la devuelve como un array asociativo.

[
  "id_usuario" => 1,
  "nombre" => "Ana",
  "apellido" => "Pérez"
]


  SQL

 adscripto.id_usuario AS id_usuario_adscripto
  * Columna de adscripto renombrada para diferenciarla de usuario.id_usuario.

 usuario.nombre_usuario, usuario.apellido_usuario
  * asociados al adscripto.

JOIN adscripto ON grupo.id_adscripto = adscripto.id_adscripto
  * Cada grupo se asocia con su adscripto.

JOIN usuario ON adscripto.id_usuario = usuario.id_usuario
  * Cada adscripto se asocia con su usuario.
*/

$con->close(); // cierro conexión cuando ya tengo todos los datos
?>

<!DOCTYPE html>
<head>
  <title> Grupos </title>
    <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS propio -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="/img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="/img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <a href="../secretario-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>
 
        <a href="../usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
        <a href="../horario/horario-secretario" class="nav-opciones">Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="fw-semibold seleccionado mb-2">Grupos</a>
        </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
          <a href="../secretario-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="../secretario-bienvenida.php">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
        </div>

        <a href="../usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
        <a href="../horario/horario-secretario.php" class="nav-opciones">Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="fw-semibold seleccionado mb-2">Grupos</a>
       </div>

    <!-- Contenido principal-->
    <div class="col-md-9 principal"> <!-- Boostrap contendio al lado del menu -->
      <img src="/img/logo.png" alt="Logo" class="logo">

      <div class="acordion-total">
  <div class="acordion">

  <div class="bloque-agregar">
              <h1 class="etiqueta">Gestión de Grupos</h1>
              <button class="agregar" 
                      data-bs-toggle="modal" 
                      data-bs-target="#modalGrupo" 
                      onclick="document.getElementById('accion').value='insertar';">
                  +
              </button>
  </div>

  
<?php while($row = $result->fetch_assoc()): ?>
<div class="dia">
    <button class="boton-opciones miercoles"><?php echo htmlspecialchars($row['nombre_grupo'], ENT_SUBSTITUTE); ?></button>
    <div class="contenido-dia">
        <table class="tabla-horario">
            <!--- htmlspecialchars = encodes html special characters such as <, ", /, etc to prevent XSS (Cross Site Scripting)-->
            <tr><td>Orientación: <?php echo htmlspecialchars($row['orientacion_grupo'], ENT_SUBSTITUTE); //se usa para eliminar etiquetas HTML y PHP de un string. ?></td></tr>
            <tr><td>Turno: <?php echo $row['turno_grupo']; ?></td></tr>
            <tr><td>Cantidad de alumnos: <?php echo $row['cantidad_alumno_grupo']; ?></td></tr>
            <tr><td>Adscripto: <?php echo htmlspecialchars($row['nombre_usuario'], ENT_SUBSTITUTE).' '. htmlspecialchars($row['apellido_usuario'], ENT_SUBSTITUTE); ?></td></tr>
        </table>

        <!-- Botones provisionales Editar / Eliminar -->
        <div class="mt-2">
            <button class="btn btn-sm btn-warning" 
                    data-bs-toggle="modal" 
                    data-bs-target="#modalGrupo"
                    onclick="cargarEditar(
                        '<?php echo $row['id_grupo']; ?>',
                        '<?php echo $row['orientacion_grupo']; ?>',
                        '<?php echo $row['turno_grupo']; ?>',
                        '<?php echo $row['nombre_grupo']; ?>',
                        '<?php echo $row['cantidad_alumno_grupo']; ?>',
                        '<?php echo $row['id_adscripto']; ?>' // muestra los datos que estan guardados (y ahora seran editados)
                    )">
                Editar
            </button>

            <form id="formEliminar<?php echo $row['id_grupo'];?>" 
                        method="POST" 
                        style="display:inline;">
                      <input type="hidden" name="accion" value="eliminar">
                      <input type="hidden" name="id_grupo" value="<?php echo $row['id_grupo']; ?>">
                      
                      <button type="button" 
                              class="btn btn-sm btn-danger eliminar-grupo-btn" 
                              data-id="<?php echo $row['id_grupo']; ?>">
                          Eliminar
                      </button>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>

            <!-- Modal para insertar o editar grupo -->
            <div class="modal fade" id="modalGrupo" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Gestión de Grupos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <form method="POST" id="formGrupo">
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
                          <?php foreach($orientaciones as $o): ?>
                            <option value="<?php echo htmlspecialchars($o, ENT_QUOTES, 'UTF-8'); ?>"></option>
                          <?php endforeach; ?>
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
                        while($adscriptoRow = $adscriptoResult->fetch_assoc()){
                            echo "<option value='".$adscriptoRow['id_adscripto']."'>".$adscriptoRow['nombre_usuario']." ".$adscriptoRow['apellido_usuario']."</option>";
                        }
                        ?>
                        </select>
                      </div>

                      <div class="mb-3"> <!-- id del secretario asociado con el grupo creado para que se gusrade en la bd -->
                        <input type="hidden" name="id_secretario" value="1"> <!-- SESSION <input type="hidden" name="id_secretario" value="<?php echo $_SESSION['id_secretario']; ?>">
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

          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Scripts -->

   <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="../js/editar.js"></script>
  <script src="../js/validation.js"></script>
  <script src="../js/desplegarCaracteristicas.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>