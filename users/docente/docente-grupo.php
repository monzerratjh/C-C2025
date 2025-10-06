<?php 
include('../../conexion.php');
include('../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos a Cargo</title>
</head>
<body>

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
      <a href="docente-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>

      <a href="docente-grupo.php">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="nav-opciones mb-2">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2">Avisar Falta</a>
    </div>
  </div>

  <!-- Contenedor general -->

  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="docente-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="docente-bienvenida.php">Volver</a>
          </div>
          <i class="bi bi-translate traductor-menu"></i>
        </div>

      <a href="docente-grupo.php" class="fw-semibold seleccionado">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="nav-opciones mb-2">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2">Avisar Falta</a>
   </div>

    <!-- Contenido principal-->
   <main class="col-md-9 principal" >
     <img src="/img/logo.png" alt="Logo" class="logo"> 
    <div>
      <h2>Grupos a Cargo</h2>
    </div>
    
<div class="acordion">
  <div>
      <button class="boton-opciones sin-flecha docente">3MD</button>
      <div class="dia">

      <div>
      <button class="boton-opciones sin-flecha docente">3MD</button>
      <div class="dia">

      <div>
      <button class="boton-opciones sin-flecha docente">3MD</button>
      <div class="dia">

            <button class="boton-opciones sin-flecha docente">3MD</button>
      <div class="dia">

      <div>
      <button class="boton-opciones sin-flecha docente">3MD</button>
      <div class="dia">

      <div>
      <button class="boton-opciones sin-flecha docente">3MD</button>
      <div class="dia"></div>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>