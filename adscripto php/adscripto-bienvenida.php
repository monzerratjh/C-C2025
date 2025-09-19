<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida adscripto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
   <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <a href="../index.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cerrar Sesión</a>

       <a href="../adscripto php/adscripto_espacio.php" class="nav-opciones">Espacios</a>
        <a href="../adscripto php/reservas-adscripto.php" class="nav-opciones">Reservas</a>
        <a href="../adscripto php/faltaDocentes.php" class="nav-opciones">Faltas docentes</a>
        <a href="../adscripto php/cargaHorarios.php" class="nav-opciones mb-2">Cargar horarios</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
       <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../index.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../index.php">Cerrar Sesión</a>
          </div>
        </div>

      <a href="../adscripto php/adscripto_espacio.php" class="nav-opciones mb-2">Espacios</a>
      <a href="../adscripto php/reservas-adscripto.php" class="nav-opciones mb-2">Reservas</a>
      <a href="../adscripto php/faltaDocentes.php" class="nav-opciones mb-2">Faltas de docentes</a>
      <a href="../adscripto php/cargaHorarios.php" class="nav-opciones mb-2">Cargar horarios</a>
    </div>


<!-- Contenido principal -->
<main class="col-md-9 principal-estudiantes" >

    <img src="../logo.png" alt="Logo" class="logo"> 
    
    <h1 class="bienvenida">Bienvenid@</h1>
    <h1 class="bienvenida">Adscript@ @username</h1>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="./form_logIn.js"></script>
</body>
</html>