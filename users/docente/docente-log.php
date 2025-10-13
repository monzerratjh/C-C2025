<?php 
//include('../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente</title>
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
      <img class="logoResponsive" src="./../../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div>
      <a href="../../index.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="./../estudiante/estudiante.php" class="nav-opciones mb-2">Estudiante</a>
      <a href="./../adscripto/adscripto-log.php" class="nav-opciones mb-2">Adscripto</a>
      <a href="./../docente/docente-log.php" class="fw-semibold seleccionado mb-2">Docente</a>
      <a href="./../secretario/secretario-log.php" class="nav-opciones mb-2">Secretario</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
           <div class="volver">
            <a href="../../index.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../../index.php">Volver</a>
          </div>
          <i class="bi bi-translate traductor-menu"></i>
        </div>

        <a href="./../estudiante/estudiante.php" class="nav-opciones">Estudiante</a>
        <a href="./../adscripto/adscripto-log.php" class="nav-opciones">Adscripto</a>
        <a href="./../docente/docente-log.php" class="fw-semibold seleccionado">Docente</a>
        <a href="./../secretario/secretario-log.php" class="nav-opciones">Secretario</a>
      </div>


<!-- Contenido principal -->
<main class="col-md-9 principal" >

    <img src="./../../img/logo.png" alt="Logo" class="logo"> 
    
    <section class="seccion-form docente">

      <div class="icono-usuario-login">
        <i class="bi bi-person-circle"></i>
      </div>
                                          
      <form id="form-login" class="formulario" action="/utils/log-in.php" method="POST"> 
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" class="form-control" placeholder="Cédula de Identidad"
          name="cedula" id="cedula" required>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text" ><i class="bi bi-lock"></i></span>
          <input type="password" class="form-control" placeholder="Contraseña"
          name="password" id="password" required>
        </div>

 <!-- Indicamos el rol -->
        <input type="hidden" name="rol" value="docente">
        <button type="submit" id="boton" name="btn-log-in" value="1">Iniciar Sesión</button>      
      </form>

    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/utils/form-log-in.js"></script>
</body>
</html>