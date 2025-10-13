<?php
//include('../../encabezado.php');
include('../../conexion.php');
session_start();
$con = conectar_bd();

// Obtener datos base
$reservas = $con->query("
    SELECT 
        asignatura_docente_solicita_espacio.id_reserva,
        asignatura.nombre_asignatura,
        espacio.nombre_espacio,
        horario_clase.hora_inicio,
        horario_clase.hora_fin,
        asignatura_docente_solicita_espacio.estado_reserva,
        asignatura_docente_solicita_espacio.fecha_hora_reserva,
        usuario.nombre_usuario
    FROM asignatura_docente_solicita_espacio
    JOIN asignatura ON asignatura_docente_solicita_espacio.id_asignatura = asignatura.id_asignatura
    JOIN espacio ON asignatura_docente_solicita_espacio.id_espacio = espacio.id_espacio
    JOIN horario_clase ON asignatura_docente_solicita_espacio.id_horario_clase = horario_clase.id_horario_clase
    JOIN usuario ON asignatura_docente_solicita_espacio.id_docente = usuario.id_usuario
    ORDER BY asignatura_docente_solicita_espacio.fecha_hora_reserva DESC
");

// Listas desplegables
$espacios = $con->query("SELECT id_espacio, nombre_espacio FROM espacio WHERE disponibilidad_espacio = 'disponible'");
$asignaturas = $con->query("SELECT id_asignatura, nombre_asignatura FROM asignatura");
$horarios = $con->query("SELECT id_horario_clase, hora_inicio, hora_fin FROM horario_clase");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservar Espacio</title>
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
      <a href="docente-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>

      <a href="docente-grupo.php" class="nav-opciones mb-2">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="fw-semibold seleccionado">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2">Avisar Falta</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="docente-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="docente-bienvenida.php">Volver</a>
          </div>
          <i class="bi bi-translate traductor-menu"></i>
        </div>

      <a href="docente-grupo.php" class="nav-opciones mb-2">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="fw-semibold seleccionado">Reservar Espacio</a>
      <a href="docente-falta.php" class="nav-opciones mb-2">Avisar Falta</a>
   </div>

    <!-- Contenido principal-->
    <div class="col-md-9 horarios-estudiante"> <!-- Boostrap contendio al lado del menu -->
     <img src="../../img/logo.png" alt="Logo" class="logo"> 

  <div class="acordion">

    <!-- Opción 1 -->
<div>
  <button class="boton-opciones docente" id="verReservasBtn">Ver reservas</button>

  <div class="dia" id="listaReservas" style="display:none;">
  <?php if ($reservas->num_rows > 0) { ?>
    <table class="tabla-reservas">
      <thead>
        <tr>
          <th>Asignatura</th>
          <th>Docente</th>
          <th>Espacio</th>
          <th>Horario</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = $reservas->fetch_assoc()) { ?>
          <tr>
            <td><?= $r['nombre_asignatura'] ?></td>
            <td><?= $r['nombre_usuario'] ?></td>
            <td><?= $r['nombre_espacio'] ?></td>
            <td><?= $r['hora_inicio'] ?> - <?= $r['hora_fin'] ?></td>
            <td><?= ucfirst($r['estado_reserva']) ?></td>
            <td>
              <?php if ($r['estado_reserva'] !== 'cancelada') { ?>
                <button class="cancelar-reserva-boton btn btn-sm btn-danger" data-id="<?= $r['id_reserva'] ?>">Cancelar</button>
              <?php } else { ?>
                <i>Cancelada</i>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    <p>No hay reservas registradas.</p>
  <?php } ?>
</div>
</div>

    <!-- Opción 2: Hacer reservas -->
<div>
  <button class="boton-opciones docente" id="hacerReservaBtn">Hacer reservas</button>

  <!-- Modal para hacer reserva -->
  <div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva Reserva</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formReserva">
            <div class="mb-3">
              <label for="asignatura" class="form-label">Asignatura</label>
              <select class="form-select" id="asignatura" name="id_asignatura" required>
                <option value="">Seleccione...</option>
                <?php while($fila = $asignaturas->fetch_assoc()) { ?>
                  <option value="<?php echo $fila['id_asignatura']; ?>"><?php echo $fila['nombre_asignatura']; ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="espacio" class="form-label">Espacio</label>
              <select class="form-select" id="espacio" name="id_espacio" required>
                <option value="">Seleccione...</option>
                <?php while($fila = $espacios->fetch_assoc()) { ?>
                  <option value="<?php echo $fila['id_espacio']; ?>"><?php echo $fila['nombre_espacio']; ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="horario" class="form-label">Horario</label>
              <select class="form-select" id="horario" name="id_horario_clase" required>
                <option value="">Seleccione...</option>
                <?php while($fila = $horarios->fetch_assoc()) { ?>
                  <option value="<?php echo $fila['id_horario_clase']; ?>">
                    <?php echo $fila['hora_inicio'] . " - " . $fila['hora_fin']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <input type="hidden" name="accion" value="insertar">

            <div class="text-end">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Reservar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!--
      <div>
      <button class="boton-opciones docente">Notificación de falta</button>
      <div class="dia"> 
        

  </div> 
-->

<!-- Bootstrap Bundle JS -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="./js/desplegar-reserva.js"></script>
<script src="./js/reserva.js"></script>
</body>
</html>