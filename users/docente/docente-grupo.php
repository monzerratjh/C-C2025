<?php 
include('../../conexion.php');
session_start(); //continua la sesion que ya esta activa

// Verificamos sesión
//si no hay sesion activa lo lleva al login
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../../login.php");
  exit;
}

$con = conectar_bd();

// Obtener el id_docente correspondiente al usuario logueado
$id_usuario = $_SESSION['id_usuario'];
//hace la consulta
$sqlDocente = "SELECT id_docente FROM docente WHERE id_usuario = ?";
//prepara la consulta
$stmt = $con->prepare($sqlDocente);
//remplaza el ? por el id del usuario
$stmt->bind_param("i", $id_usuario);
$stmt->execute(); //ejecuta
$result = $stmt->get_result(); //obtiene los resultados 
$docente = $result->fetch_assoc(); // guarda el resultado en el array

if (!$docente) { // si no se encuntra nada en la consulta, se detiene la ejecucion y se muestra el mensaje
  die("<p>No se encontró un docente asociado a este usuario.</p>");
}
$id_docente = $docente['id_docente']; // si esta todo bien se guarda el id_docente

// Obtener los grupos asignados al docente (sin duplicados - DISTINCT)
$sqlGrupos = "
  SELECT DISTINCT g.id_grupo, g.nombre_grupo, g.orientacion_grupo, g.turno_grupo
  FROM grupo_asignatura_docente_aula gada
  JOIN grupo g ON g.id_grupo = gada.id_grupo
  WHERE gada.id_docente = ? 
  ORDER BY g.turno_grupo, g.nombre_grupo
";

//prepara ejecuta y obtiene los resultados del grupo
$stmt2 = $con->prepare($sqlGrupos);
$stmt2->bind_param("i", $id_docente);
$stmt2->execute();
$grupos = $stmt2->get_result();


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel docente</title>
    <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
   <!-- CSS propio -->
    <link rel="stylesheet" href="./../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none"> <!-- Oculta el nav en pantallas medianas hacia arriba -->
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"> <!-- Se abre el menu tipo offcanvas (panel lateral) -->
        <img class="menuResponsive" src="./../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">  <!-- off-canvas-start hace qeu el menu se abra desde la izquierda y -1 hace que el menu sea enfocable-->
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="./docente-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="docente-grupo.php" class="fw-semibold seleccionado mb-2" data-i18n="assignedGroups">Grupos a Cargo</a>
      <a href="./reserva/docente-reservar.php" class="nav-opciones mb-2" data-i18n="reserveFacility">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2" data-i18n="reportAbsence">Avisar Falta</a>
    
       <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
    </div>
  </div>

  <!-- Contenedor general con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="./docente-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="./docente-bienvenida.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="docente-grupo.php" class="fw-semibold seleccionado" data-i18n="assignedGroups">Grupos a Cargo</a>
      <a href="./reserva/docente-reservar.php" class="nav-opciones mb-2" data-i18n="reserveFacility">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2" data-i18n="reportAbsence">Avisar Falta</a>
    
       <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>

    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../img/logo.png" alt="Logo" class="logo"> 

      <div>
        <h2 id="tituloGruposCargo" data-i18n="assignedGroups">Grupos a Cargo</h2>
      </div>
      <br>       
      <p data-i18n="selectGroupViewSchedule">Selecciona un grupo para ver sus horarios.</p>

      <div class="caja-grupos-cargo">
        <div class="acordion">
          <?php if ($grupos->num_rows > 0): ?>     <!-- si el numero de columnas en mayor a 0 (el docente tiene un grupo) -->
            <?php while ($g = $grupos->fetch_assoc()): ?> <!-- lo recorre -->
  <div class="dia">
    <button class="boton-opciones docente" data-id="<?= $g['id_grupo'] ?>"> <!-- es un atajo, es lo mimso que poner echo-->
      <?= htmlspecialchars($g['nombre_grupo']) ?> 
      <span class="capacidad-modal">(<?= htmlspecialchars($g['turno_grupo']) ?>)</span>
    </button><!-- se muestra el nombre y el turno principalmente-->

    <!-- Contenedor oculto por defecto -->
    <div class="contenido-grupo grupos-docente-responsive">
      <table class="tabla-docente">
        <thead>
          <tr>
            <th data-i18n="onlyDay">Día</th>
            <th data-i18n="subject">Asignatura</th>
            <th data-i18n="startTime">Hora Inicio</th>
            <th data-i18n="endTime">Hora Fin</th>
            <th data-i18n="facility">Espacio</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

      <!-- Tabla responsive para móviles -->
      <table class="tabla-docente-responsive">
        <tbody></tbody>
      </table>
    </div>
  </div>
<?php endwhile; ?>

   <?php else: ?> <!-- Sino muestra que no tiene grupos asignados -->
            <p class="text-muted" data-i18n="anyGroupAssigned">No tienes grupos asignados actualmente.</p>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="./../../utils/form-log-in.js"></script> 

 <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../utils/translate.js"></script>

<script src="./js/grupo.js"></script>
</body>
</html>