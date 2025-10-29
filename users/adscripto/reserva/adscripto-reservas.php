<?php
include('./../../../conexion.php');
session_start();
$con = conectar_bd();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  header('Location: ./../../../index.php');
  exit;
}

$nombre_adscripto = $_SESSION['nombre_usuario'] ?? '';
$apellido_adscripto = $_SESSION['apellido_usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel adscripto</title>
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
        <a href="./adscripto-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      
      <a href="./../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../reserva/reserva-adscripto.php" class="fw-semibold seleccionado mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2" data-i18n="courseManagement" >Gestión de cursos</a>

       <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>

    </div>
  </div>

  <!-- Contenedor general con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="./../adscripto-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="./../adscripto-bienvenida.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../reserva/reserva-adscripto.php" class="fw-semibold seleccionado mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2" data-i18n="courseManagement"  >Gestión de cursos</a>
    
     <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>

 </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../img/logo.png" alt="Logo" class="logo"> 

  <div class="container py-4"> <br><br><br>
    <h2 data-i18n="reservationRequests">Solicitudes de Reserva</h2>
      <p data-i18n="viewReservations">Consulta las reservas realizadas por cada docente y los espacios solicitados.</p>

   <table class="tabla-reserva" id="tablaReservas"> <br>
            <thead class="table-light">
              <tr>
                <th>Docente</th>
                <th>Grupo</th>
                <th>Asignatura</th>
                <th>Espacio</th>
                <th>Día</th>
                <th>Fecha</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
                <th>Estado</th>
                <th>Acción</th>
                
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        <div id="sinReservas" class="text-muted">Cargando reservas...</div>
      </div>

  <script src="./../js/adscripto-reservas.js"></script>
</body>
</html>