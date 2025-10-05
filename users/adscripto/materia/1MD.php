<?php 
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primero MD horarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
      <a href="../adscripto-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="../espacio/adscripto-espacio.php" class="nav-opciones mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docente</a>
      <a href="carga-materias.php" class="fw-semibold seleccionado mb-2">Materias</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../adscripto-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../adscripto-bienvenida.php">Volver</a>
          </div>
            <i class="bi bi-translate traductor-menu"></i>
        </div>

       <a href="../espacio/adscripto-espacio.php" class="nav-opciones mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docente</a>
      <a href="carga-materias.php" class="fw-semibold seleccionado mb-2">Materias</a>
    </div>


<!-- Contenido principal -->
<main class="col-md-9 principal" >


    <img src="../../img/logo.png" alt="Logo" class="logo"> 

    
    <h1>Cargar horarios 1MD</h1>

    <table class="tabla-reserva">
      <thead>
        <tr>
          <th>Horario de entrada</th>
          <th>Horario de salida</th>
          <th>Materia</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
       <tr>
          <td>10:15</td>
          <td>11:05 </td>
          <td>Programacion</td>
          <td><i class="bi bi-pencil"></i></td>
          <td><i class="bi bi-trash"></i></td>
        </tr>
        <tr>
          <td>10:15</td>
          <td>11:05</td>
           <td>Programacion</td>
          <td><i class="bi bi-pencil"></i></td>
          <td><i class="bi bi-trash"></i></td>
        </tr>
       <tr>
          <td>10:15</td>
          <td>11:05</td>
           <td>Programacion</td>
          <td><i class="bi bi-pencil"></i></td>
          <td><i class="bi bi-trash"></i></td>
        </tr>
        <tr>
          <td>10:15</td>
          <td>11:05</td>
          <td>ingles</td>
          <td><i class="bi bi-pencil"></i></td>
          <td><i class="bi bi-trash"></i></td>
        </tr>
          <tr>
         <td>10:15</td>
          <td>11:05</td>
          <td>ingles</td>
          <td><i class="bi bi-pencil"></i></td>
          <td><i class="bi bi-trash"></i></td>
        </tr>
       <tr>
         <td>10:15</td>
          <td>11:05</td>
          <td>ingles</td>
          <td><i class="bi bi-pencil"></i></td>
          <td><i class="bi bi-trash"></i></td>    
        </tr>

        <tr>
            <td> </td>
            <td><h2>+</h2></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
      </tbody>
    </table>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="redireccionar-grupo.js"></script>
</body>
</html>