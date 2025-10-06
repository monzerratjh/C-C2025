<?php 
//include('../../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario 2MB</title>
    <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS propio -->
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../../../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div>
      <a href="../estudiante.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="../../estudiante/estudiante.php" class="fw-semibold seleccionado mb-2">Estudiantes</a>
      <a href="../../adscripto/adscripto-log.php" class="nav-opciones mb-2">Adscriptos</a>
      <a href="../../docente/docente-log.php" class="nav-opciones mb-2">Docente</a>
      <a href="../../secretario/secretario-log.php" class="nav-opciones mb-2">Secretaría</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../estudiante.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../estudiante.php">Volver</a>
          </div>
           <i class="bi bi-translate traductor-menu"></i>
        </div>

        <a href="../../estudiante/estudiante.php" class="fw-semibold seleccionado">Estudiante</a>
        <a href="../../adscripto/adscripto-log.php" class="nav-opciones">Adscripto</a>
        <a href="../../docente/docente-log.php" class="nav-opciones">Docente</a>
        <a href="../../secretario/secretario-log.php" class="nav-opciones">Secretario</a>
      </div>

    <!-- Contenido horarios-estudiante-->
    <div class="col-md-9 horarios-estudiante">
      <img src="../../../img/logo.png" alt="Logo" class="logo">

      <div class="acordion-total">
        <div class="acordion">
          <h2 class="titulo-horario">2°MB</h2>

          <!-- LUNES -->
          <div class="dia">
            <button class="boton-opciones lunes">Lunes</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
                <tr><th>Hora</th><th>Materia</th><th>Espacio</th></tr>
                <tr><td>07:00</td><td>Ingenieria</td><td>Salón 4</td></tr>
                <tr><td>07:50</td><td>Sistemas Operativos</td><td>Aula 3</td></tr>
                <tr><td>08:40</td><td>Ingles Tecnico</td><td>Salón 4</td></tr>
                <tr><td>09:30</td><td>Ingles Tenico</td><td>Salón 4</td></tr>
                <tr><td>10:20</td><td>Profundicación</td><td>Salón 4</td></tr>
                <tr><td>11:10</td><td>Profundicación</td><td>Salón 4</td></tr>
                <tr><td>12:00</td><td>Filosofía</td><td>Salón 4</td></tr>
                <tr><td>12:50</td><td>Profundicación</td><td>Salón 4</td></tr>
                <tr><td>13:40</td><td>Tutorias UTULAB</td><td>Salón 4</td></tr>
                <tr><td>14:30</td><td>Tutorias UTULAB</td><td>Salón 4</td></tr>
              </table>
            </div>
          </div>

          <!-- MARTES -->
          <div class="dia">
            <button class="boton-opciones martes">Martes</button>
            <div class="contenido-dia">
               <table class="tabla-horario">
                <tr><th>Hora</th><th>Materia</th><th>Espacio</th></tr>
                <tr><td>08:40</td><td>Ingles Tecnico</td><td>Salón 4</td></tr>
                <tr><td>09:30</td><td>Ingles Tenico</td><td>Salón 4</td></tr>
                <tr><td>10:20</td><td>Profundicación</td><td>Salón 4</td></tr>
                <tr><td>11:10</td><td>Profundicación</td><td>Salón 4</td></tr>
                <tr><td>12:00</td><td>Filosofía</td><td>Salón 4</td></tr>
                <tr><td>12:50</td><td>Filosofía</td><td>Salón 4</td></tr>
                <tr><td>13:40</td><td>Sociologia</td><td>Salón 4</td></tr>
                <tr><td>14:30</td><td>Sociologia</td><td>Salón 4</td></tr>
              </table>
            </div>
          </div>

          <!-- MIÉRCOLES -->
          <div class="dia">
            <button class="boton-opciones miercoles">Miércoles</button>
            <div class="contenido-dia">
               <table class="tabla-horario">
                <tr><th>Hora</th><th>Materia</th><th>Espacio</th></tr>
                <tr><td>07:50</td><td>Ciberseguridad</td><td>Salón 4</td></tr>
                <tr><td>08:40</td><td>Ciberseguridad</td><td>Salón 4</td></tr>
                <tr><td>09:30</td><td>Emprendurismo</td><td>Salón 4</td></tr>
                <tr><td>10:20</td><td>Emprendurismo</td><td>Salón 4</td></tr>
                <tr><td>11:10</td><td>Matemática</td><td>Salón 4</td></tr>
                <tr><td>12:00</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>12:50</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>13:40</td><td>Fisica</td><td>Salón 4</td></tr>
                <tr><td>14:30</td><td>Gestion de proyecto en ingles</td><td>Salón 4</td></tr>
                <tr><td>15:20</td><td>Gestion de proyecto en ingles</td><td>Salón 4</td></tr>
              </table>
            </div>
          </div>

          <!-- JUEVES -->
          <div class="dia">
            <button class="boton-opciones jueves">Jueves</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
                <tr><th>Hora</th><th>Materia</th><th>Espacio</th></tr>
                <tr><td>07:50</td><td>Sistemas operativos</td><td>Aula 3</td></tr>
                <tr><td>08:40</td><td>Sistemas operativos</td><td>Aula 3</td></tr>
                <tr><td>09:30</td><td>Fisica</td><td>Salón 4</td></tr>
                <tr><td>10:20</td><td>Fisica</td><td>Salón 4</td></tr>
                <tr><td>11:10</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>12:00</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>12:50</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>14:30</td><td>Gestion de proyecto en ingles</td><td>Salón 4</td></tr>
                <tr><td>15:20</td><td>Gestion de proyecto en ingles</td><td>Salón 4</td></tr>
                <tr><td>13:40</td><td>Ingenieria</td><td>Salón 4</td></tr>
              </table>
            </div>
          </div>


          <!-- VIERNES -->
          <div class="dia">
            <button class="boton-opciones viernes">Viernes</button>
            <div class="contenido-dia">
             <table class="tabla-horario">
                <tr><th>Hora</th><th>Materia</th><th>Espacio</th></tr>
                <tr><td>08:40</td><td>Matemática</td><td>Salón 4</td></tr>
                <tr><td>09:30</td><td>Matemática</td><td>Salón 4</td></tr>
                <tr><td>10:20</td><td>Calculo</td><td>Salón 4</td></tr>
                <tr><td>11:10</td><td>Calculo</td><td>Salón 4</td></tr>
                <tr><td>12:00</td><td>Ingenieria</td><td>Salón 4</td></tr>
                <tr><td>12:50</td><td>Ingenieria</td><td>Salón 4</td></tr>
                <tr><td>13:40</td><td>Sociologia</td><td>Salón 4</td></tr>
                </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- JS acordeón -->
  <script src="../js/desplegar-horario.js"></script>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
