<?php 
include('../../../conexion.php');
$con = conectar_bd();

// Obtener valores del ENUM 'disponibilidad_espacio' para el select
$enumConsulta = $con->query("SHOW COLUMNS FROM espacio LIKE 'disponibilidad_espacio'");
$filaEnum = $enumConsulta->fetch_assoc();
preg_match_all("/'([^']+)'/", $filaEnum['Type'], $coincidenciasEnum);
$valoresDisponibilidad = $coincidenciasEnum[1];

// Detectar tipo según archivo
$nombreArchivo = basename($_SERVER['PHP_SELF']);
$tipoDetectado = '';
if (strpos($nombreArchivo, 'laboratorio') !== false) $tipoDetectado = 'Laboratorio';
elseif (strpos($nombreArchivo, 'aula') !== false) $tipoDetectado = 'Aula';
elseif (strpos($nombreArchivo, 'salon') !== false) $tipoDetectado = 'Salón';

// Traer espacios de ese tipo
$resultadoEspacios = $con->query("SELECT * FROM espacio WHERE tipo_espacio = '$tipoDetectado' ORDER BY nombre_espacio");

$con->close();
?>
<!DOCTYPE html>
<html lang="es">
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
      <img class="logoResponsive" src="./../../../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
      <a href="adscripto-espacio.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i><span data-i18n="goBack">Volver</span></a>
      <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2" data-i18n="facility">Espacio</a>
      <a href="./../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2" data-i18n="courseManagement">Gestión de cursos</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <!-- Contenedor general con GRID -->
<div class="contenedor">

  <!-- Banner pantallas grandes -->
  <div class="barra-lateral d-none d-md-flex">
    <div class="volverGeneral">
      <div class="volver">
        <a href="adscripto-espacio.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
        <a href="adscripto-espacio.php" data-i18n="goBack">Volver</a>
      </div>
      <i class="bi bi-translate traductor-menu"></i>
    </div>

    <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2" data-i18n="facility">Espacio</a>
    <a href="./../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
    <a href="./../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
    <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2" data-i18n="courseManagement">Gestión de cursos</a>
  </div>

  <!-- Contenido principal -->
  <main class="principal">
    <img src="./../../../img/logo.png" alt="Logo" class="logo"> 

    <div class="container my-4 espacio-contenedor">
      <h2 data-i18n="scienceLabs">Laboratorios</h2>
      <p data-i18n="manageScienceLabs">Agrega, edita o elimina laboratorios de forma rápida y sencilla.</p>
     <div class="row justify-content-center mt-4">

    <!-- Agregar nuevo primero -->
    <div class="col-6 mb-4">
      <div class="espacio-card espacio-agregar d-flex justify-content-center align-items-center">
        <button id="button-plus" data-bs-toggle="modal" data-bs-target="#modalEspacio" onclick="prepararNuevoEspacio()">
          <span class="espacio-plus">+</span>
        </button>
      </div>
    </div>

    <!-- Luego los espacios existentes -->
    <?php while($espacio = $resultadoEspacios->fetch_assoc()): ?>
      <div class="col-6 mb-4">
        <div class="espacio-card">
          <div class="espacio-cuerpo"></div>
          <div class="espacio-footer d-flex justify-content-between align-items-center">
            
            <button class="btn btn-sm btn-light"
              data-bs-toggle="modal" data-bs-target="#modalEspacio"
              onclick='cargarEditarEspacio(<?=json_encode($espacio)?>)'>
              <i class="bi bi-pencil-square"></i>
            </button>

            <span><?=htmlspecialchars($espacio['nombre_espacio'])?></span>

            <button type="button" class="btn btn-sm btn-light btn-danger eliminar-espacio-boton" data-id="<?= $espacio['id_espacio'] ?>">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>
    <?php endwhile; ?>

</div>
      <!-- Modal -->
      <div class="modal fade" id="modalEspacio" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" data-i18n="spaceManagement">Gestión de espacio</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formularioEspacio" method="POST">
              <div class="modal-body">
                <input type="hidden" id="accion" name="accion">
                <input type="hidden" id="id_espacio" name="id_espacio">
                <input type="hidden" id="tipo_espacio" name="tipo_espacio" value="<?= $tipoDetectado ?>">

                <div class="mb-3">
                  <label for="nombre_espacio" data-i18n="spaceName">Nombre del espacio</label>
                  <input type="text" id="nombre_espacio" name="nombre_espacio" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label for="capacidad_espacio"> <span data-i18n="capacity">Capacidad</span> <span class="capacidad-modal" data-i18n="maxStudents">(Máx. de alumnos)</span></label>
                  <input type="number" id="capacidad_espacio" name="capacidad_espacio" class="form-control" required min="1" max="50">
                </div>
                <div class="mb-3">
                  <label for="disponibilidad_espacio" data-i18n="availability">Disponibilidad</label>
                  <select id="disponibilidad_espacio" name="disponibilidad_espacio" class="form-control" required>
                    <option value="" data-i18n="select">Seleccione...</option>
                    <?php foreach($valoresDisponibilidad as $valor): ?>
                      <option value="<?= htmlspecialchars($valor, ENT_QUOTES) ?>"><?= htmlspecialchars($valor) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="historial_espacio" data-i18n="historyNotes">Historial / Observaciones</label>
                  <textarea id="historial_espacio" name="historial_espacio" class="form-control" rows="3"></textarea>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="cancel">Cancelar</button>
                <button type="submit" class="btn btn-primary" data-i18n="save">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/espacio.js"></script>



  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>

</body>
</html>