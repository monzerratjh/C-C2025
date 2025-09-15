<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar espacios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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

<div class="container my-4 espacio-contenedor">

<!-- Título con input -->
<div class="espacio-titulo centrar text-center mb-4">
  <input type="text" class="espacio-input" placeholder="Editar nombre">
</div>

  <!-- Formulario -->
  <form class="text-center">

    <div class="row fw-bold mb-2">
      <div class="col-4"></div>
      <div class="col-4">Cuenta con:</div>
      <div class="col-4">Cantidad:</div>
    </div>

    <!-- Fila Mesas -->
    <div class="row align-items-center mb-2">
      <div class="col-4 text-end">Mesas</div>
      <div class="col-4"><input type="checkbox" class="form-check-input"></div>
      <div class="col-4"><input type="number" class="form-control form-control-sm bg-light border-0"></div>
    </div>

    <!-- Fila Sillas -->
    <div class="row align-items-center mb-2">
      <div class="col-4 text-end">Sillas</div>
      <div class="col-4"><input type="checkbox" class="form-check-input"></div>
      <div class="col-4"><input type="number" class="form-control form-control-sm bg-light border-0"></div>
    </div>

    <!-- Fila Proyector -->
    <div class="row align-items-center mb-2">
      <div class="col-4 text-end">Proyector</div>
      <div class="col-4"><input type="checkbox" class="form-check-input"></div>
      <div class="col-4"><input type="number" class="form-control form-control-sm bg-light border-0"></div>
    </div>

    <!-- Fila Tele -->
    <div class="row align-items-center mb-2">
      <div class="col-4 text-end">Tele</div>
      <div class="col-4"><input type="checkbox" class="form-check-input"></div>
      <div class="col-4"><input type="number" class="form-control form-control-sm bg-light border-0"></div>
    </div>

    <!-- Fila Aire -->
    <div class="row align-items-center mb-4">
      <div class="col-4 text-end">Aire</div>
      <div class="col-4"><input type="checkbox" class="form-check-input"></div>
      <div class="col-4"><input type="number" class="form-control form-control-sm bg-light border-0"></div>
    </div>

    <!-- Botón -->
    <div class="text-center">
      <button type="submit" class="btn centrar espacio-btn">Guardar cambios</button>
    </div>
  </form>
</div>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="./form_logIn.js"></script>
</body>
</html>