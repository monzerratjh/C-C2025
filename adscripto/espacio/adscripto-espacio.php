<?php 
include('../../encabezado.php');
?>
<!DOCTYPE html>
<head>
    <title>Espacio - Adscripto</title>
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
      <a href="adscripto-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>

       <a href="../adscripto/adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
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
            <a href="adscripto-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="adscripto-bienvenida.php">Volver</a>
          </div>
        </div>

       <a href="../adscripto/adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="../adscripto/reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../adscripto/falta-docente.php" class="nav-opciones mb-2">Falta docente</a>
      <a href="../adscripto/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
    </div>

<!-- Contenido principal -->
<main class="col-md-9 principal-estudiantes" >

    <img src="../../img/logo.png" alt="Logo" class="logo"> 
    
    
    <div class="acordion">

    <!-- Opcion 1 -->
      <div>
      <a href="/adscripto/index-salon.php"><button class="boton-opciones adscripto">Salones</button></a>
      <div class="dia">

      <div>
      <a href="/adscripto/index-aula.php"><button class="boton-opciones adscripto">Aulas</button></a>
      <div class="dia">

      <div>
      <a href="/adscripto/index-laboratorio.php"><button class="boton-opciones adscripto">Laboratorios</button></a>
      <div class="dia">

    </div>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="./form_logIn.js"></script>
</body>
</html>