<?php 
include('../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Falta docentes</title>
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
      <div>
          <a href="./adscripto-bienvenida.php" class="mb-3">
            <i class="bi bi-arrow-left-circle-fill me-2"></i>
            <span data-i18n="goBack">Volver</span>
          </a>
          <i class="bi bi-translate traductor-menu"></i>
        </div>

          <a href="espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
          
          <a href="./reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      
          <a href="falta-docente.php" class="fw-semibold seleccionado mb-2" data-i18n="teacherAbsence">Falta docente</a>

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
          
      <a href="./reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      
      <a href="falta-docente.php" class="fw-semibold seleccionado mb-2" data-i18n="teacherAbsence">Falta docente</a>

      <a href="materia/carga-materias.php" class="nav-opciones mb-2" data-i18n="addSubjects">Cargar materias</a>
    </div>

    <!-- Contenido principal-->
    <div class="col-md-9 horarios-estudiante"> <!-- Boostrap contendio al lado del menu -->
      <img src="../../img/logo.png" alt="Logo" class="logo">

      <div class="acordion-total">
  <div class="acordion">

    <div class="bloque-agregar">
    <h2 data-i18n="teacherAbsence">Falta docentes</h2>
  </div>

          <div class="dia">
              <button class="boton-opciones miercoles">Docente 1</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
               <tr><td>Nombre: Fcaundo Rubil</td></tr>
               <tr><td>Materia: Programacion</td></tr>
                <tr><td>Grupo a la que falta: 3MD</td></tr>
                <tr><td>Dia: 20/10/2025</td></tr>
                <tr><td>Cuantas horas falta: 3</td></tr>
              </table>
            </div>
          </div>

          <div class="dia">
            <button class="boton-opciones miercoles">Docente 1</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
               <tr><td>Nombre: Fcaundo Rubil</td></tr>
               <tr><td>Materia: Programacion</td></tr>
                <tr><td>Grupo a la que falta: 3MD</td></tr>
                <tr><td>Dia: 20/10/2025</td></tr>
                <tr><td>Cuantas horas falta: 3</td></tr>
              </table>
            </div>
          </div>


           <div class="dia">
            <button class="boton-opciones miercoles">Docente 1</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
               <tr><td>Nombre: Fcaundo Rubil</td></tr>
               <tr><td>Materia: Programacion</td></tr>
                <tr><td>Grupo a la que falta: 3MD</td></tr>
                <tr><td>Dia: 20/10/2025</td></tr>
                <tr><td>Cuantas horas falta: 3</td></tr>
              </table>
            </div>
          </div>


           <div class="dia">
            <button class="boton-opciones miercoles">Docente 1</button>
            <div class="contenido-dia">
              <table class="tabla-horario">
               <tr><td>Nombre: Fcaundo Rubil</td></tr>
               <tr><td>Materia: Programacion</td></tr>
                <tr><td>Grupo a la que falta: 3MD</td></tr>
                <tr><td>Dia: 20/10/2025</td></tr>
                <tr><td>Cuantas horas falta: 3</td></tr>
              </table>
            </div>
          </div>
          </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
<script src="js/desplegarFaltas.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>

  <script src="/utils/translate.js"></script>
</body>
</html>