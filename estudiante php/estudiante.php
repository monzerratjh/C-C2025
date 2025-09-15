<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel Estudiante</title>

  <!-- Bootstrap CSS + Iconos + letras-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/style.css">
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
      <a href="../index.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>

      <a href="/estudiante php/estudiante.php" class="fw-semibold seleccionado mb-2">Estudiante</a>
      <a href="/adscripto php/adscripto_log.php" class="nav-opciones mb-2">Adscripto</a>
      <a href="/docente php/docente_log.php" class="nav-opciones mb-2">Docente</a>
      <a href="/secretario php/secretario_log.php" class="nav-opciones mb-2">Secretario</a>
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
            <a href="../index.php">Volver</a>
          </div>
        </div>

        <a href="/estudiante php/estudiante.php" class="fw-semibold seleccionado">Estudiante</a>
        <a href="/adscripto php/adscripto_log.php" class="nav-opciones">Adscripto</a>
        <a href="/docente php/docente_log.php" class="nav-opciones">Docente</a>
        <a href="/secretario php/secretario_log.php" class="nav-opciones">Secretario</a>
        
      </div>

      <!-- Contenido principal -->
      <div class="col-md-9 col-12 principal-estudiantes">
        <img src="/img/logo.png" alt="Logo" class="logo"> 
        <h2>ESTUDIANTE</h2>
        <p>Ingresa tu grupo correspondiente</p>

        <div class="busqueda-estudiantes">
          <i class="bi bi-search icono-busqueda"></i>
          <input type="text" class="diseno-busqueda" placeholder="Ingrese su grupo" list="lista-grupos" id="grupoInput" />
          <datalist id="lista-grupos">
            <option value="1° MD">
            <option value="2° MD">
            <option value="3° MD">
            <option value="3° MB">
          </datalist>
        </div>
      </div>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="app.js"></script>
</body>
</html>
