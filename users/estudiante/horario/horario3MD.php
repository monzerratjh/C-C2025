<?php 
include ('./../../../conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario 3MD</title>
    <!-- Bootstrap CSS + Iconos + letras -->
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

  <!-- Menú lateral (offcanvas para móviles) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="../estudiante.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a><i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="./../../estudiante/estudiante.php" class="fw-semibold seleccionado mb-2" data-i18n="student">Estudiantes</a>
      <a href="./../../adscripto/adscripto-log.php" class="nav-opciones mb-2" data-i18n="adscripto">Adscriptos</a>
      <a href="./../../docente/docente-log.php" class="nav-opciones mb-2" data-i18n="teacher">Docente</a>
      <a href="./../../secretario/secretario-log.php" class="nav-opciones mb-2" data-i18n="secretary">Secretaría</a>
   </div>
  </div>

  <!-- Contenedor principal usando Grid -->
  <div class="contenedor">

    <!-- Barra lateral fija -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../estudiante.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="../estudiante.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../estudiante/estudiante.php" class="fw-semibold seleccionado" data-i18n="student">Estudiante</a>
      <a href="./../../adscripto/adscripto-log.php" class="nav-opciones" data-i18n="adscripto">Adscripto</a>
      <a href="./../../docente/docente-log.php" class="nav-opciones" data-i18n="teacher">Docente</a>
      <a href="./../../secretario/secretario-log.php" class="nav-opciones" data-i18n="secretary">Secretario</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../img/logo.png" alt="Logo" class="logo">

      <div class="acordion-total">
        <div class="acordion">
          <h2 class="titulo-horario">3°MD</h2>

          <!-- DÍAS DE LA SEMANA -->
          <div class="dia">
           <button class="boton-opciones  colorletrablanco lunes" data-i18n="monday">Lunes</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
                <tr><th data-i18n="hour">Hora</th><th data-i18n="subject">Materia</th><th data-i18n="room">Espacio</th></tr>
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

          <div class="dia">
            <button class="boton-opciones colorletrablanco martes" data-i18n="tuesday">Martes</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
               <tr><th data-i18n="hour">Hora</th><th data-i18n="subject">Materia</th><th data-i18n="room">Espacio</th></tr>
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

          <div class="dia">
            <button class="boton-opciones colorletrablanco miercoles" data-i18n="wednesday">Miércoles</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
                <tr><th data-i18n="hour">Hora</th><th data-i18n="subject">Materia</th><th data-i18n="room">Espacio</th></tr>
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

          <div class="dia">
            <button class="boton-opciones colorletrablanco jueves" data-i18n="thursday">Jueves</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
               <tr><th data-i18n="hour">Hora</th><th data-i18n="subject">Materia</th><th data-i18n="room">Espacio</th></tr>
                <tr><td>07:50</td><td>Sistemas operativos</td><td>Aula 3</td></tr>
                <tr><td>08:40</td><td>Sistemas operativos</td><td>Aula 3</td></tr>
                <tr><td>09:30</td><td>Fisica</td><td>Salón 4</td></tr>
                <tr><td>10:20</td><td>Fisica</td><td>Salón 4</td></tr>
                <tr><td>11:10</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>12:00</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>12:50</td><td>Programación</td><td>Salón 4</td></tr>
                <tr><td>13:40</td><td>Gestion de proyecto en ingles</td><td>Salón 4</td></tr>
                <tr><td>14:30</td><td>Gestion de proyecto en ingles</td><td>Salón 4</td></tr>
              </table>
            </div>
          </div>

          <div class="dia">
            <button class="boton-opciones colorletrablanco viernes" data-i18n="friday">Viernes</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
                <tr><th data-i18n="hour">Hora</th><th data-i18n="subject">Materia</th><th data-i18n="room">Espacio</th></tr>
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

        </div>
      </div>
    </main>

  </div>



  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="./../../../utils/desplegar-acordeon.js"></script>

  <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>
</body>
</html>
