<?php 
include('../../conexion.php');
session_start();

// Verificamos sesión
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../../login.php");
  exit;
}

$con = conectar_bd();

// Obtener el id_docente correspondiente al usuario logueado
$id_usuario = $_SESSION['id_usuario'];
$sqlDocente = "SELECT id_docente FROM docente WHERE id_usuario = ?";
$stmt = $con->prepare($sqlDocente);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$docente = $result->fetch_assoc();

if (!$docente) {
  die("<p>No se encontró un docente asociado a este usuario.</p>");
}
$id_docente = $docente['id_docente'];

// Obtener los grupos asignados al docente (sin duplicados)
$sqlGrupos = "
  SELECT DISTINCT g.id_grupo, g.nombre_grupo, g.orientacion_grupo, g.turno_grupo
  FROM grupo_asignatura_docente_aula gada
  JOIN grupo g ON g.id_grupo = gada.id_grupo
  WHERE gada.id_docente = ?
  ORDER BY g.turno_grupo, g.nombre_grupo
";
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
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="./../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
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
      <p>Selecciona un grupo para ver sus horarios.</p>

      <div class="caja-grupos-cargo">
        <div class="acordion">
          <?php if ($grupos->num_rows > 0): ?>
            <?php while ($g = $grupos->fetch_assoc()): ?>
  <div class="dia">
    <button class="boton-opciones docente" data-id="<?= $g['id_grupo'] ?>">
      <?= htmlspecialchars($g['nombre_grupo']) ?>
      <span class="capacidad-modal">(<?= htmlspecialchars($g['turno_grupo']) ?>)</span>
    </button>

    <!-- Contenedor oculto por defecto -->
    <div class="contenido-grupo grupos-docente-responsive">
      <table class="tabla-docente">
        <thead>
          <tr>
            <th>Día</th>
            <th>Asignatura</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <th>Espacio</th>
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

   <?php else: ?>
            <p class="text-muted">No tienes grupos asignados actualmente.</p>
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