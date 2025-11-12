<?php 
include('../../../conexion.php');
$con = conectar_bd();

// Detectar tipo por nombre de archivo
$file = basename($_SERVER['PHP_SELF']);
$tipoDetectado = '';
if (strpos($file, 'laboratorio') !== false) $tipoDetectado = 'Laboratorio';
elseif (strpos($file, 'aula') !== false) $tipoDetectado = 'Aula';
elseif (strpos($file, 'salon') !== false) $tipoDetectado = 'Salón';

// Traer espacios de ese tipo
$espacios = $con->query("SELECT * FROM espacio WHERE tipo_espacio = '$tipoDetectado' ORDER BY nombre_espacio");
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
     
    
      <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
    
    
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
  
  
      <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
    
  
    </div>

  <!-- Contenido principal -->
  <main class="principal">

    <img src="./../../../img/logo.png" alt="Logo" class="logo"> 

    <div class="container my-4 espacio-contenedor">
      <h2 data-i18n="computerLabs">Aulas</h2>
      <p data-i18n="manageComputersLabs">Agrega, edita o elimina aulas de forma rápida y sencilla.</p>


  <div class="row justify-content-center mt-4">
    <!-- Agregar -->
    <div class="col-6 mb-4">
      <div class="espacio-card espacio-agregar d-flex justify-content-center align-items-center">
        <button id="button-plus" data-bs-toggle="modal" data-bs-target="#modalEspacio" onclick="prepararNuevoEspacio()">
          <span class="espacio-plus">+</span>
        </button>
      </div>
    </div>

    <?php while($esp = $espacios->fetch_assoc()): ?>
    <div class="col-6 mb-4">
      
    <!-- Muestra la tarjetas con los diversos atributos del espacio el cual los trae desde el array $espacios -->    
    <div class="espacio-card cursor-pointer" data-id="<?= (int)$esp['id_espacio'] ?>">
        
      <div class="espacio-cuerpo"> </div>

        <div class="espacio-footer d-flex justify-content-between align-items-center">
          <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalEspacio"
                  onclick='cargarEditarEspacio(<?= json_encode($esp) ?>)'>
            <i class="bi bi-pencil-square"></i>
          </button>

          <span><?= htmlspecialchars($esp['nombre_espacio']) ?></span>

          <button class="btn btn-sm btn-light eliminar-espacio-boton" data-id="<?= (int)$esp['id_espacio'] ?>">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
    
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEspacio" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 id="modalTitulo" class="modal-title" data-i18n="addFacility">Agregar espacio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- PASO 1: datos del espacio -->

        <form id="formPaso1" autocomplete="off" novalidate>
          <input type="hidden" id="accion" name="accion" value="crear">
          <input type="hidden" id="id_espacio" name="id_espacio">
          <input type="hidden" id="tipo_espacio" name="tipo_espacio" value="<?= htmlspecialchars($tipoDetectado) ?>">

          <div class="mb-3">
            <label for="nombre_espacio" class="form-label" data-i18n="nameFacility">Nombre del espacio</label>
            <input type="text" id="nombre_espacio" name="nombre_espacio" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="capacidad_espacio"> <span data-i18n="capacity">Capacidad</span> <span class="capacidad-modal">(Máx. de alumnos)</span></label>
            <input type="number" id="capacidad_espacio" name="capacidad_espacio" class="form-control" min="1" max="100" required>
          </div>

         

          <div class="mb-3">
            <label for="historial_espacio" class="form-label" data-i18n="historyNotes">Historial / Observaciones</label>
            <textarea id="historial_espacio" name="historial_espacio" class="form-control" rows="3"></textarea>
          </div>
        </form>

        <!-- PASO 2: atributos del espacio -->
        <form id="formPaso2" class="d-none">
          <h6 class="text-center mb-3" data-i18n="attributesFacility">Atributos del espacio</h6>
          <input type="hidden" id="id_espacio_attr" name="id_espacio">

          <?php foreach (['Mesas','Sillas','Proyector','Televisor','Aire_Acondicionado','Computadora_de_escritorio','Enchufes','Ventilador'] as $attr): ?>
            <div class="row align-items-center mb-2">
              <div class="col-4 text-end"><?= str_replace('_',' ',$attr) ?></div>
              <div class="col-4"><input type="checkbox" class="form-check-input toggleCantidad" data-target="cant_<?= $attr ?>"></div>
              <div class="col-4"><input type="number" name="<?= strtolower($attr) ?>" id="cant_<?= $attr ?>" class="form-control form-control-sm bg-light border-0" disabled></div>
            </div>
          <?php endforeach; ?>

          <!-- Campo "Otro" -->
          <div class="row align-items-center mb-2">
            <div class="col-4 text-end" data-i18n="otherSpecify">Otro (especificar)</div>
            <div class="col-4"><input type="checkbox" class="form-check-input toggleCantidad" data-target="otro_personalizado"></div>
            <div class="col-4">
              <input type="text" name="otro_descripcion" id="otro_descripcion" class="form-control form-control-sm mb-1" data-i18n-placeholder="nameAttribute" disabled>
              <input type="number" name="otro_cantidad" id="otro_personalizado" class="form-control form-control-sm bg-light border-0" data-i18n-placeholder="quantity" disabled>
            </div>
          </div>
        </form>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="cancel">Cancelar</button>
        <button class="btn btn-primary" id="btnSiguiente" data-i18n="next">Siguiente</button>
        <button class="btn btn-success d-none" id="btnGuardarAtributos" data-i18n="save">Guardar</button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="./../js/espacio.js"></script>

<script src="./../../../../utils/form-log-in.js"></script>

  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>

</body>
</html>