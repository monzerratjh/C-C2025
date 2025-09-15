<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-body d-flex flex-column">
      <p class="fw-semibold seleccionado mb-">USERNAME</p>
      <a href="adscripto_espacios.html" class="fw-semibold seleccionado mb-2">Espacios</a>
      <a href="adscripto_reservas.html" class="nav-opciones mb-2">Reservas</a>
      <a href="adscripto_faltasDocentes.html" class="nav-opciones mb-2">Faltas de docentes</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
         <p class="fw-semibold seleccionado usuario mb-">USERNAME</p>
        <a href="adscripto_espacios.html" class="fw-semibold seleccionado">Espacios</a>
        <a href="adscripto_reservas.html" class="nav-opciones">Reservas</a>
        <a href="adscripto_faltasDocentes.html" class="nav-opciones">Faltas docentes</a>
         </div>

<!-- Contenido principal -->
<main class="col-md-9 principal-estudiantes" >

    <img src="img/logo.png" alt="Logo" class="logo"> 

    <div class="container my-4 espacio-contenedor">
  <div class="espacio-titulo text-center">
    <h3>Salones</h3>
  </div>

  <div class="row justify-content-center mt-4">
    
    <!-- Salón 1 -->
    <div class="col-6 mb-4">
      <div class="espacio-card">
        <div class="espacio-cuerpo"></div>
        <div class="espacio-footer d-flex justify-content-between align-items-center">
          <button class="btn btn-sm btn-light">
            <i class="bi bi-pencil-square"></i>
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
            <i class="bi bi-pencil-square"></i>
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
            <i class="bi bi-pencil-square"></i>
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
        <span class="espacio-plus">+</span>
      </div>
    </div>
  </div>
</div>

    

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="./form_logIn.js"></script>
</body>
</html>