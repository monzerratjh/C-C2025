<?php 
//include('../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de reservas</title>
    <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS propio -->
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
 <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div>
        <a href="./adscripto-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      
      <a href="espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./reserva-adscripto.php" class="fw-semibold seleccionado mb-2" data-i18n="reservation">Reserva</a>
      <a href="./falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="materia/carga-materias.php" class="nav-opciones mb-2" data-i18n="addSubjects">Cargar materias</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="./adscripto-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="./adscripto-bienvenida.php" data-i18n="goBack">Volver</a>
          </div>
            <i class="bi bi-translate traductor-menu"></i>
        </div>

      <a href="espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./reserva-adscripto.php" class="fw-semibold seleccionado mb-2" data-i18n="reservation">Reserva</a>
      <a href="./falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="materia/carga-materias.php" class="nav-opciones mb-2" data-i18n="addSubjects">Cargar materias</a>
    </div>


  <main class="col-md-9 principal">
    <img src="../../img/logo.png" alt="Logo" class="logo"> 

    <h2 data-i18n="reservationRequests">Solicitudes de Reserva</h2>

    <table class="tabla-reserva">
      <thead>
        <tr>
          <th data-i18n="thTeacher">Docente</th>
          <th data-i18n="thFacilityRequested">Espacio solicitado</th>
          <th data-i18n="thRequestedDate">Fecha Solicitada</th>
          <th data-i18n="thReservationStatus">Estado reserva</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Facundo Rubil</td>
          <td>Aula 1</td>
          <td>09/08/2025</td>
          <td class="confirmada">CONFIRMADA</td>
        </tr>
        <tr>
          <td>Ines Lopez</td>
          <td>Aula 4</td>
          <td>09/08/2025</td>
          <td class="pendiente">A CONFIRMAR</td>
        </tr>
        <tr>
          <td>Facundo Rubil</td>
          <td>Aula 1</td>
          <td>09/08/2025</td>
          <td class="confirmada">CONFIRMADA</td>
        </tr>
        <tr>
          <td>Ines Lopez</td>
          <td>Aula 4</td>
          <td>09/08/2025</td>
          <td class="pendiente">A CONFIRMAR</td>
        </tr>
        <tr>
          <td>Facundo Rubil</td>
          <td>Aula 1</td>
          <td>09/08/2025</td>
          <td class="confirmada">CONFIRMADA</td>
        </tr>
        <tr>
          <td>Ines Lopez</td>
          <td>Aula 4</td>
          <td>09/08/2025</td>
          <td class="pendiente">A CONFIRMAR</td>
        </tr>
      </tbody>
    </table>
  </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>

  <script src="/utils/translate.js"></script>
</body>
</html>