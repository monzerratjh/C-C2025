<?php 
include('../../conexion.php');
//include('../../encabezado.php');
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
      <a href="docente-reservar.php" class="nav-opciones mb-2" data-i18n="reserveFacility">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2" data-i18n="reportAbsence">Avisar Falta</a>
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
      <a href="docente-reservar.php" class="nav-opciones mb-2" data-i18n="reserveFacility">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2" data-i18n="reportAbsence">Avisar Falta</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../img/logo.png" alt="Logo" class="logo"> 

      <div>
        <h2 id="tituloGruposCargo" data-i18n="assignedGroups">Grupos a Cargo</h2>
      </div>
      <div class="caja-grupos-cargo">
        <div class="acordion">
          <div>
            <button class="boton-opciones sin-flecha docente">3MD</button>
            <div class="dia"></div>
          </div>

          <div>
            <button class="boton-opciones sin-flecha docente">3MD</button>
            <div class="dia"></div>
          </div>

          <div>
            <button class="boton-opciones sin-flecha docente">3MD</button>
            <div class="dia"></div>
          </div>

          <div>
            <button class="boton-opciones sin-flecha docente">3MD</button>
            <div class="dia"></div>
          </div>

          <div>
            <button class="boton-opciones sin-flecha docente">3MD</button>
            <div class="dia"></div>
          </div>

          <div>
            <button class="boton-opciones sin-flecha docente">3MD</button>
            <div class="dia"></div>
          </div>
        </div>
  </div>
    </main>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

 <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../utils/translate.js"></script>

</body>
</html>
