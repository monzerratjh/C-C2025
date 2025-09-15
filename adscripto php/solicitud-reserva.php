<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de reservas</title>
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
      <a href="adscripto_espacios.html" class="nav-opciones mb-2">Espacios</a>
      <a href="adscripto_reservas.html" class="fw-semibold seleccionado mb-2">Reservas</a>
      <a href="adscripto_faltasDocentes.html" class="nav-opciones mb-2">Faltas de docentes</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
         <p class="fw-semibold seleccionado usuario mb-">USERNAME</p>
        <a href="adscripto_espacios.html" class="nav-opciones">Espacios</a>
        <a href="adscripto_reservas.html" class="fw-semibold seleccionado">Reservas</a>
        <a href="adscripto_faltasDocentes.html" class="nav-opciones">Faltas docentes</a>
         </div>


  <main class="contenedor-reservas">

    <img src="img/logo.png" alt="Logo" class="logo"> 

    
    <div class="reserva-titulo">
      <h3>Solicitudes de Reserva</h3>
    </div>

    <table class="tabla-reserva">
      <thead>
        <tr>
          <th>Docente</th>
          <th>Espacio solicitado</th>
          <th>Fecha Solicitada</th>
          <th>Estado reserva</th>
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
<script src="./form_logIn.js"></script>
</body>
</html>