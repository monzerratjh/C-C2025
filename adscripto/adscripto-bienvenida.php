<?php 
include('../encabezado.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida adscripto</title>
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
      <a href="../log-out.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cerrar Sesión</a>

       <a href="" class="nav-opciones">Espacio</a>
        <a href="../adscripto/reserva-adscripto.php" class="nav-opciones">Reserva</a>
        <a href="../adscripto/falta-docente.php" class="nav-opciones">Falta docente</a>
        <a href="../adscripto/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
       <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../log-out.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../log-out.php">Cerrar Sesión</a>
          </div>
        </div>

      <a href="../adscripto/adscripto-espacio.php" class="nav-opciones mb-2">Espacio</a>
      <a href="../adscripto/reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../adscripto/falta-docente.php" class="nav-opciones mb-2">Falta docente</a>
      <a href="../adscripto/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
    </div>


<!-- Contenido principal -->
<main class="col-md-9 principal-estudiantes" >

    <img src="../img/logo.png" alt="Logo" class="logo"> 
    
    <h1 class="bienvenida">Bienvenid@</h1>
    <h1 class="bienvenida">Adscript@ @username</h1>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../form-logIn.js"></script>
</body>
</html>