<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservas adscripto</title>

  <!-- Bootstrap CSS + Iconos + letras-->
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
      <a href="adscripto-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>

       <a href="/adscripto php/adscripto_espacio.php" class="nav-opciones">Espacios</a>
        <a href="/adscripto php/reservas-adscripto.php" class="fw-semibold seleccionado mb-2">Reservas</a>
        <a href="/adscripto php/faltaDocentes.php" class="nav-opciones">Faltas docentes</a>
        <a href="/adscripto php/cargaHorarios.php" class="nav-opciones mb-2">Cargar horarios</a>
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

       <a href="/adscripto php/adscripto_espacio.php" class="nav-opciones mb-2">Espacios</a>
      <a href="/adscripto php/reservas-adscripto.php" class="fw-semibold seleccionado mb-2">Reservas</a>
      <a href="/adscripto php/faltaDocentes.php" class="nav-opciones mb-2">Faltas de docentes</a>
      <a href="/adscripto php/cargaHorarios.php" class="nav-opciones mb-2">Cargar horarios</a>
    </div>

    <!-- Contenido principal-->
    <div class="col-md-9 horarios-estudiantes"> <!-- Boostrap contendio al lado del menu -->
      <img src="../logo.png" alt="Logo" class="logo">

  <div class="acordion">

    <!-- Opcion 1 -->
      <div>
      <a href="solicitud-reserva.php"><button class="boton-opciones adscripto">Solicitudes de Reserva </button></a>
      <div class="dia">

      <div>
      <button class="boton-opciones adscripto">Espacios Predeterminados</button>
      <div class="dia">
  </div>

<!-- Bootstrap JS -->
<script src="app.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
