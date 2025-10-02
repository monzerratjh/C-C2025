<?php
include('../../conexion.php');
include('../../encabezado.php');
$con = conectar_bd();

// Consulta para obtener todos los grupos con información del adscripto y usuario
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
");

/*
 adscripto.id_usuario AS id_usuario_adscripto
  * Columna de adscripto renombrada para diferenciarla de usuario.id_usuario.

 usuario.nombre_usuario, usuario.apellido_usuario
  * asociados al adscripto.

JOIN adscripto ON grupo.id_adscripto = adscripto.id_adscripto
  * Cada grupo se asocia con su adscripto.

JOIN usuario ON adscripto.id_usuario = usuario.id_usuario
  * Cada adscripto se asocia con su usuario.
*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Grupos - secretario</title>

  <!-- Bootstrap CSS + Iconos + letras-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../../img/logo.png" alt="logoRespnsive">
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
      <img src="../../img/logo.png" alt="Logo" class="logo">

      <div class="acordion-total">
  <div class="acordion">

  <div class="bloque-agregar">
              <button class="etiqueta">Grupos</button>
              <button class="agregar" 
                      data-bs-toggle="modal" 
                      data-bs-target="#modalGrupo" 
                      onclick="document.getElementById('accion').value='insertar';">
                  +
              </button>
  </div>

  
<?php while($row = $result->fetch_assoc()): ?>
<div class="dia">
    <button class="boton-opciones miercoles"><?php echo $row['nombre_grupo']; ?></button>
    <div class="contenido-dia">
        <table class="tabla-horario">
            <tr><td>Orientación: <?php echo $row['orientacion_grupo']; ?></td></tr>
            <tr><td>Turno: <?php echo $row['turno_grupo']; ?></td></tr>
            <tr><td>Cantidad de alumnos: <?php echo $row['cantidad_alumno_grupo']; ?></td></tr>
            <tr><td>Adscripto: <?php echo $row['nombre_usuario'].' '.$row['apellido_usuario']; ?></td></tr>
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
                        action="grupo-accion.php" 
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


<?php while($row = $result->fetch_assoc()){ ?>
              <tr>
                <td><?php echo $row['id_grupo']; ?></td>
                <td><?php echo $row['nombre_grupo']; ?></td>
                <td><?php echo $row['orientacion_grupo']; ?></td>
                <td><?php echo $row['turno_grupo']; ?></td>
                <td><?php echo $row['cantidad_alumno_grupo']; ?></td>
                <td><?php echo $row['nombre_usuario']." ".$row['apellido_usuario']; ?></td>
                <td>
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
                </td>
              </tr>
              <?php } ?>
            </table>

            <!-- Modal para insertar o editar grupo -->
            <div class="modal fade" id="modalGrupo" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Grupo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <form method="POST" action="grupo-accion.php" id="formGrupo">
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
                        $adscriptoResult = $con->query("SELECT adscripto.id_adscripto, usuario.nombre_usuario, usuario.apellido_usuario 
                                                         FROM adscripto 
                                                         JOIN usuario ON adscripto.id_usuario=usuario.id_usuario");
                        while($adscriptoRow = $adscriptoResult->fetch_assoc()){
                            echo "<option value='".$adscriptoRow['id_adscripto']."'>".$adscriptoRow['nombre_usuario']." ".$adscriptoRow['apellido_usuario']."</option>";
                        }
                        ?>
                        </select>
                      </div>

                      <div class="mb-3">
                        <input type="hidden" name="id_secretario" value="1">
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

  <script src="../js/grupo.js"></script>
  <script src="../js/validation.js"></script>
  <script src="../js/desplegarCaracteristicas.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

 <!-- Mostrar mensaje del servidor con SweetAlert2 -->
<?php if(isset($_GET['message']) && $_GET['message'] != ''): ?>
<script>
Swal.fire({
  icon: '<?php echo $_GET['type'] ?? 'success'; ?>',
  title: '<?php echo ($_GET['type'] ?? 'success') == 'error' ? 'Error' : 'Éxito'; ?>',
  text: '<?php echo addslashes($_GET['message']); ?>',
  timer: 2000,
  showConfirmButton: false
});

// limpiar los parámetros para que al recargar no vuelva a aparecer
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.pathname);
}
</script>
<?php endif; ?>


</body>
</html>