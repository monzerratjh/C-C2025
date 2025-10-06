<?php 
include('../../conexion.php');
include('../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservar Espacio</title>
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
      <a href="docente-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>

      <a href="docente-grupo.php" class="nav-opciones mb-2">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="fw-semibold seleccionado">Reservar Espacio</a>
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

      <a href="docente-grupo.php" class="nav-opciones mb-2">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="fw-semibold seleccionado">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2">Avisar Falta</a>
   </div>

    <!-- Contenido principal-->
    <div class="col-md-9 horarios-estudiante"> <!-- Boostrap contendio al lado del menu -->
     <img src="/img/logo.png" alt="Logo" class="logo"> 

  <div class="acordion">

    <!-- Opcion 1 -->
      <div>
      <button class="boton-opciones docente">Ver reservas</button>
      <div class="dia">

      <div>
      <button class="boton-opciones docente">Hacer reservas</button>
      <div class="dia">

      <div>
      <button class="boton-opciones docente">Notificación de falta</button>
      <div class="dia">

  </div>

<!-- Bootstrap JS -->
<script src="app.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
