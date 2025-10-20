<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Espacio - Adscripto</title>
  
  <!-- Bootstrap CSS + Iconos + letras-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  
  <!-- CSS propio -->
  <link rel="stylesheet" href="./../../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="./../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="../adscripto-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>Volver
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="./../espacio/adscripto-espacio.php" class="fw-semibold seleccionado mb-2" data-i18n="facility">Espacio</a> 
      <a href="./../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2">Gestión de cursos</a>
    </div>
  </div>

  <!-- Contenedor general con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../adscripto-bienvenida.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="../adscripto-bienvenida.php">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../espacio/adscripto-espacio.php" class="fw-semibold seleccionado mb-2" data-i18n="facility">Espacio</a> 
      <a href="./../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2">Gestión de cursos</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../img/logo.png" alt="Logo" class="logo"> 
      
      <div class="acordion">
        <h2>Gestión de espacios</h2>
        <p>Seleccione el tipo de espacio que desea gestionar:</p>

        <div>
          <a href="index-salon.php">
            <button class="boton-opciones2 adscripto">Salones</button>
          </a>
        </div>

        <div>
          <a href="index-aula.php">
            <button class="boton-opciones2 adscripto">Aulas</button>
          </a>
        </div>

        <div>
          <a href="index-laboratorio.php">
            <button class="boton-opciones2 adscripto">Laboratorios</button>
          </a>
        </div>
      </div>
    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>
</body>
</html>