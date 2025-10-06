<?php 
//include('../../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salones</title>
    
    <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS propio -->
    <link rel="stylesheet" href="../../../css/style.css">
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
      <div>
      <a href="adscripto-espacio.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>
      </div>

       <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docentes</a>
      <a href="../materia/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="adscripto-espacio.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="adscripto-espacio.php">Volver</a>
          </div>
          <i class="bi bi-translate traductor-menu"></i>
        </div>

       <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docentes</a>
      <a href="../materia/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
    </div>

<!-- Contenido principal -->
<main class="col-md-9 principal" >

    <img src="/img/logo.png" alt="Logo" class="logo"> 

    <div class="container my-4 espacio-contenedor">
  
    <h2>Salones</h2>


  <div class="row justify-content-center mt-4">
    
    <!-- Salón 1 -->
    <div class="col-6 mb-4">
      <div class="espacio-card">
        <div class="espacio-cuerpo"></div>
        <div class="espacio-footer d-flex justify-content-between align-items-center">
          <button class="btn btn-sm btn-light">
            <a href="editar-propiedad-espacio.php"><i class="bi bi-pencil-square"></i></a>
          </button>
          <span>Salón 1</span>
          <button class="btn btn-sm btn-light">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Salón 2 -->
    <div class="col-6 mb-4">
      <div class="espacio-card">
        <div class="espacio-cuerpo"></div>
        <div class="espacio-footer d-flex justify-content-between align-items-center">
          <button class="btn btn-sm btn-light">
            <a href="editar-propiedad-espacio.php"><i class="bi bi-pencil-square"></i></a>
          </button>
          <span>Salón 2</span>
          <button class="btn btn-sm btn-light">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Salón 3 -->
    <div class="col-6 mb-4">
      <div class="espacio-card">
        <div class="espacio-cuerpo"></div>
        <div class="espacio-footer d-flex justify-content-between align-items-center">
          <button class="btn btn-sm btn-light">
            <a href="editar-propiedad-espacio.php"><i class="bi bi-pencil-square"></i></a>
          </button>
          <span>Salón 3</span>
          <button class="btn btn-sm btn-light">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Agregar -->
    <div class="col-6 mb-4">
      <div class="espacio-card espacio-agregar d-flex justify-content-center align-items-center">
      <a href="agregar-espacio.php"><span class="espacio-plus">+</span></a>
      </div>
    </div>
  </div>
</div>

    

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>