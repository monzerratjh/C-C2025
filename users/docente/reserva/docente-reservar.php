<?php
include('./../../../conexion.php');
session_start(); //sigue la sesion que ya estaba iniciada
$con = conectar_bd();

$id_usuario = $_SESSION['id_usuario'] ?? null; //extrae el id del docente que esta en la sesion
if (!$id_usuario) {
  header('Location: ./../../../users/docente/docente-log.php'); //si no existe lo lleva al login
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel docente</title>

  <!-- Bootstrap + Iconos + fuentes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- CSS propio -->
  <link rel="stylesheet" href="./../../../css/style.css">


</head>

<body class="grupo-user-page">

  <!-- Menú móvil -->
  <nav class="d-md-none"> <!-- Oculta el nav en pantallas medianas hacia arriba -->
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"> <!-- Se abre el menu tipo offcanvas (panel lateral) -->
        <img class="menuResponsive" src="./../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">  <!-- off-canvas-start hace qeu el menu se abra desde la izquierda y -1 hace que el menu sea enfocable-->
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="./../docente-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="./../docente-grupo.php" class="nav-opciones mb-2" data-i18n="assignedGroups">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="fw-semibold seleccionado" data-i18n="reserveFacility">Reservar Espacio</a>
      <a href="./../docente-falta.php" class="nav-opciones mb-2" data-i18n="reportAbsence">Avisar Falta</a>
    </div>

    
      <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>

  </div>

  <!-- Contenedor general -->
  <div class="contenedor">
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="./../docente-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="./../docente-bienvenida.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../docente-grupo.php" class="nav-opciones mb-2" data-i18n="assignedGroups">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="fw-semibold seleccionado" data-i18n="reserveFacility">Reservar Espacio</a>
      <a href="./../docente-falta.php" class="nav-opciones mb-2" data-i18n="reportAbsence">Avisar Falta</a>
   
      
      <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
   
    </aside>

    <!-- Contenido principal -->
    <main class="principal horarios-estudiante">
      <img src="./../../../img/logo.png" alt="Logo" class="logo"> 

       <div>
        <h2 id="tituloReservas" data-i18n="reserveFacility">Reservar espacio</h2>
        <br>
      </div>  
      <p data-i18n="pReserveFacility">Realiza reservas de espacios y consulta el estado de tus solicitudes actuales.</p>


      <div class="acordion">
        <!-- Botón Hacer reservas -->
        <div class="mt-4"> <!-- Da margen arriba-->
          <button class="boton-opciones docente sin-flecha" id="hacerReservaBtn" data-bs-toggle="modal" data-bs-target="#modalReserva" data-i18n="makeReservations">
            Hacer reservas  
          </button>
        </div>


<div>
  <!-- Reservas: responsive -->
      <button class="boton-opciones docente sin-flecha" id="verReservasBtn" data-i18n="viewReservations">
        Ver reservas
      </button>

      <div class="tabla-docente-responsive " id="reservasResponsive">
        <div class="dia" id="reservasMovil">
          <!-- JS cards-->
        </div>
        <p id="sinReservasMovil" class="text-muted text-center mt-2"></p>
      </div>
      </div>
      <!-- Fin acorden -->
      </div>

      <!-- Tabla para pantallas grandes -->
      <div class="table-responsive" id="reservasDesktop">
        <table class="tabla-docente" id="tablaReservas">
          <thead>
            <tr>
              <th data-i18n="group">Grupo</th>
              <th data-i18n="subject">Asignatura</th>
              <th data-i18n="facility">Espacio</th>
              <th data-i18n="onlyDay">Día</th>
              <th data-i18n="date">Fecha</th>
              <th data-i18n="startTime">Hora inicio</th>
              <th data-i18n="endTime">Hora fin</th>
              <th data-i18n="state">Estado</th>
              <th data-i18n="action">Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <div id="sinReservasDesktop" class="text-muted"></div>
      </div>


    </main>
  </div>



  <!-- Modal de Reserva -->
  <div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true"> <!-- Activa la funcionalidad de modal. / Agrega animación de aparición/desaparición.-->
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- centra el modal y le da el tamaño -->
      <div class="modal-content">
        <div class="modal-header docente">
          <h5 class="modal-title" data-i18n="newReservation" data-i18n="newReservation" >Nueva Reserva</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formReserva">
            <div class="mb-3"> <!-- Margen inferior -->
              <label class="form-label" data-i18n="groupAndSubject">Grupo y asignatura</label>
              <select class="form-select" id="id_gada" name="id_grupo" required>
                <option value="">Cargando...</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" data-i18n="reservationDate">Fecha de reserva</label>
              <input type="date" class="form-control" id="fecha_reserva" required>
            </div>

            <div class="mb-3">
              <label for="cantidad_horas" class="form-label" data-i18n="howManyHours">¿Cuántas horas desea reservar?</label>
              <input type="number" id="cantidad_horas" name="cantidad_horas" class="form-control" min="1" disabled>
              <p id="msgHoras" class="text-muted small mt-1" data-i18n="selectGroupDate"> <!-- Texto aclaratorio pequeño y gris. -->
                Seleccione primero un grupo y una fecha donde tenga clase. 
              </p>
            </div>

            <div id="contenedorHorarios" class="mb-3"></div>

            <div class="mb-3">
              <label class="form-label" data-i18n="facility">Espacio</label>
              <select class="form-select" id="id_espacio" required disabled>
                <option value="" data-i18n="selectDateSchedule">Seleccione fecha y horarios primero</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" data-i18n="observation">Observación (opcional)</label>
              <textarea class="form-control" id="observacion" rows="2" maxlength="200"></textarea>
            </div>

            <div class="text-end">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="cancel">Cancelar</button>
              <button type="submit" class="btn btn-primary docente" data-i18n="send">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="./../js/desplegar-reserva.js"></script>
  <script src="./../js/reserva.js"></script> 
  <script src="./../../../utils/form-log-in.js"></script> 

 <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>
</body>
</html>
