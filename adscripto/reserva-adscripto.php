<?php 
include('../encabezado.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de reservas</title>
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
      <a href="reservas-adscripto.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>

       <a href="/adscripto/adscripto-espacio.php" class="nav-opciones">Espacio</a>
        <a href="/adscripto/reserva-adscripto.php" class="fw-semibold seleccionado mb-2">Reserva</a>
        <a href="/adscripto/falta-docente.php" class="nav-opciones">Falta docente</a>
        <a href="/adscripto/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="reserva-adscripto.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="reserva-adscripto.php">Volver</a>
          </div>
        </div>

       <a href="../adscripto/adscripto-espacio.php" class="nav-opciones mb-2">Espacio</a>
      <a href="../adscripto/reserva-adscripto.php" class="fw-semibold seleccionado mb-2">Reserva</a>
      <a href="../adscripto/falta-docente.php" class="nav-opciones mb-2">Falta docente</a>
      <a href="../adscripto/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
    </div>


  <main class="contenedor-reservas">

    <img src="../img/logo.png" alt="Logo" class="logo"> 

    
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
<script src="./form-logIn.js"></script>
</body>
</html>