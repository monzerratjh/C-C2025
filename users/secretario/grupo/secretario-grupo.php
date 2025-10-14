<?php
include('../../../conexion.php');
session_start(); 

$id_secretario = $_SESSION['id_secretario'] ?? null;
$con = conectar_bd();

$result = $con->query("
    SELECT grupo.id_grupo,
           grupo.nombre_grupo,
           grupo.orientacion_grupo,
           grupo.turno_grupo,
           grupo.cantidad_alumno_grupo,
           adscripto.id_adscripto,
           usuario.nombre_usuario,
           usuario.apellido_usuario
    FROM grupo
    JOIN adscripto ON grupo.id_adscripto = adscripto.id_adscripto
    JOIN usuario ON adscripto.id_usuario = usuario.id_usuario
    ORDER BY grupo.nombre_grupo ASC
");

$adscriptoResult = $con->query("
    SELECT adscripto.id_adscripto, usuario.nombre_usuario, usuario.apellido_usuario
    FROM adscripto
    JOIN usuario ON adscripto.id_usuario = usuario.id_usuario
");

$ENUMresult = $con->query("SHOW COLUMNS FROM grupo LIKE 'orientacion_grupo'");
$rowENUM = $ENUMresult->fetch_assoc();
preg_match_all("/'([^']+)'/", $rowENUM['Type'], $coincidencias);
$orientaciones = $coincidencias[1];
$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Secretario</title>

  <!-- Bootstrap CSS + Iconos + Letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  
  <!-- CSS propio -->
  <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa (móviles) -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral móvil -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div>
        <a href="../secretario-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>Volver
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="../usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
      <a href="../horario/horario-secretario.php" class="nav-opciones">Horarios</a>
      <a href="../grupo/secretario-grupo.php" class="fw-semibold seleccionado">Grupos</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="contenedor">

    <!-- Barra lateral (versión escritorio) -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../secretario-bienvenida.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="../secretario-bienvenida.php">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="../usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
      <a href="../horario/horario-secretario.php" class="nav-opciones">Horarios</a>
      <a href="../grupo/secretario-grupo.php" class="fw-semibold seleccionado">Grupos</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="../../../img/logo.png" alt="Logo" class="logo">
      <h2>GESTIÓN DE GRUPOS</h2>

      <div class="acordion-total">
        <div class="bloque-agregar">
          <p class="etiqueta">Listado de grupos</p>
          <button class="agregar" 
                  data-bs-toggle="modal" 
                  data-bs-target="#modalGrupo"
                  onclick="document.getElementById('accion').value='insertar';">
            +
          </button>
        </div>

        <!-- Listado de grupos -->
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="dia">
          <button class="boton-opciones miercoles">
            <?php echo htmlspecialchars($row['nombre_grupo'], ENT_SUBSTITUTE); ?>
          </button>
          <div class="contenido-dia">
            <table class="tabla-horario">
              <tr><td><b>Orientación:</b> <?php echo htmlspecialchars($row['orientacion_grupo']); ?></td></tr>
              <tr><td><b>Turno:</b> <?php echo htmlspecialchars($row['turno_grupo']); ?></td></tr>
              <tr><td><b>Cantidad de alumnos:</b> <?php echo $row['cantidad_alumno_grupo']; ?></td></tr>
              <tr><td><b>Adscripto:</b> <?php echo htmlspecialchars($row['nombre_usuario'].' '.$row['apellido_usuario']); ?></td></tr>
            </table>

            <div class="mt-2">
              <button class="btn btn-warning btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#modalGrupo"
                      onclick="cargarEditar(
                        '<?php echo $row['id_grupo']; ?>',
                        '<?php echo $row['orientacion_grupo']; ?>',
                        '<?php echo $row['turno_grupo']; ?>',
                        '<?php echo $row['nombre_grupo']; ?>',
                        '<?php echo $row['cantidad_alumno_grupo']; ?>',
                        '<?php echo $row['id_adscripto']; ?>'
                      )">
                Editar
              </button>

              <form method="POST" style="display:inline;" id="formEliminar<?php echo $row['id_grupo'];?>">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="id_grupo" value="<?php echo $row['id_grupo']; ?>">
                <button type="button" class="btn btn-danger btn-sm eliminar-grupo-btn" data-id="<?php echo $row['id_grupo']; ?>">Eliminar</button>
              </form>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </main>
  </div>

  <!-- Modal grupo -->
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
              <label>Orientación</label>
              <input type="text" class="form-control" name="orientacion" list="orientaciones" required>
              <datalist id="orientaciones">
                <?php foreach($orientaciones as $o): ?>
                <option value="<?php echo htmlspecialchars($o); ?>"></option>
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
                <?php while($ads = $adscriptoResult->fetch_assoc()): ?>
                <option value="<?php echo $ads['id_adscripto']; ?>">
                  <?php echo $ads['nombre_usuario'].' '.$ads['apellido_usuario']; ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>

            <input type="hidden" name="id_secretario" value="<?php echo htmlspecialchars($id_secretario); ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../js/grupo.js"></script>
  <script src="../js/desplegarCaracteristicas.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>