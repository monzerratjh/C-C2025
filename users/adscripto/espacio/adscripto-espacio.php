<?php
// include('../../../encabezado.php');
include('../../../conexion.php');
$con = conectar_bd();
session_start();

// Obtener todos los espacios y la cantidad de recursos asociados a cada uno
$sqlConsultaEspacios = "
    SELECT espacio.id_espacio,
           espacio.nombre_espacio,
           espacio.capacidad_espacio,
           espacio.historial_espacio,
           espacio.disponibilidad_espacio,
           (SELECT COUNT(*) FROM recurso WHERE recurso.id_espacio = espacio.id_espacio) AS cantidad_recursos
    FROM espacio
    ORDER BY espacio.nombre_espacio ASC
";
$resultadoEspacios = $con->query($sqlConsultaEspacios);

// Obtener valores del ENUM 'disponibilidad_espacio' para el select
$enumConsulta = $con->query("SHOW COLUMNS FROM espacio LIKE 'disponibilidad_espacio'");
$filaEnum = $enumConsulta->fetch_assoc();
preg_match_all("/'([^']+)'/", $filaEnum['Type'], $coincidenciasEnum);
$valoresDisponibilidad = $coincidenciasEnum[1];

$con->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espacio - Adscripto</title>
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
      <a href="../adscripto-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docentes</a>
      <a href="../materia/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
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

       <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docentes</a>
      <a href="../materia/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
    </div>

<!-- Contenido principal -->
<main class="col-md-9 principal" >

    <img src="../../../img/logo.png" alt="Logo" class="logo"> 
    
    
    <div class="acordion">

    <div class="d-flex justify-content-between align-items-center mb-3">
          <h2>Gestión de espacios</h2>
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEspacio" onclick="prepararNuevoEspacio()">
            + Nuevo espacio
          </button>
    </div>

    <!-- Opcion 1 -->
      <div>
      <a href="index-salon.php"><button class="boton-opciones adscripto">Salones</button></a>
      <div class="dia">

      <div>
      <a href="index-aula.php"><button class="boton-opciones adscripto">Aulas</button></a>
      <div class="dia">

      <div>
      <a href="index-laboratorio.php"><button class="boton-opciones adscripto">Laboratorios</button></a>
      <div class="dia">
<!--
      <div class="text-end">
  <button class="btn btn-sm btn-warning mb-1"
          data-bs-toggle="modal" data-bs-target="#modalEspacio"
          onclick='cargarEditarEspacio(
              <?php echo json_encode($filaEspacio['id_espacio']); ?>,
              <?php echo json_encode($filaEspacio['nombre_espacio']); ?>,
              <?php echo json_encode($filaEspacio['tipo']); ?>,
              <?php echo json_encode((int)$filaEspacio['capacidad_espacio']); ?>,
              <?php echo json_encode($filaEspacio['disponibilidad_espacio']); ?>,
              <?php echo json_encode($filaEspacio['historial_espacio']); ?>
          )'>
    Editar
  </button>


                  <form id="formEliminarEspacio<?php echo $filaEspacio['id_espacio']; ?>" style="display:inline;">
                    <button type="button" class="btn btn-sm btn-danger eliminar-espacio-boton" data-id="<?php echo $filaEspacio['id_espacio']; ?>">
                      Eliminar
                    </button>
                  </form>
                </div> -->

        <!-- Modal Insertar / Editar Espacio -->
        <div class="modal fade" id="modalEspacio" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Gestión de espacio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <form id="formularioEspacio" method="POST">
                <div class="modal-body">
                  <input type="hidden" id="accion" name="accion">
                  <input type="hidden" id="id_espacio" name="id_espacio">

                  <div class="mb-3">
                    <label>Nombre del espacio</label>
                    <input type="text" id="nombre_espacio" name="nombre_espacio" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Tipo de espacio</label>
                    <select id="tipo_espacio" name="tipo_espacio" class="form-control" required>
                      <option value="">Seleccione...</option>
                      <option value="Salón">Salón</option>
                      <option value="Aula">Aula</option>
                      <option value="Laboratorio">Laboratorio</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Capacidad</label>
                    <input type="number" id="capacidad_espacio" name="capacidad_espacio" class="form-control" required min="1" max="500">
                  </div>

                  <div class="mb-3">
                    <label>Disponibilidad</label>
                    <select id="disponibilidad_espacio" name="disponibilidad_espacio" class="form-control" required>
                      <option value="">Seleccione...</option>
                      <?php foreach($valoresDisponibilidad as $valorDisponibilidad): ?>
                        <option value="<?php echo htmlspecialchars($valorDisponibilidad, ENT_QUOTES); ?>"><?php echo htmlspecialchars($valorDisponibilidad, ENT_SUBSTITUTE); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Historial / Observaciones</label>
                    <textarea id="historial_espacio" name="historial_espacio" class="form-control" rows="3"></textarea>
                  </div>

                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              </form>
            </div>
          </div>



    </div>

</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../js/espacio.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!--
        <div class="list-group">
          <?php while($filaEspacio = $resultadoEspacios->fetch_assoc()): ?>
            <div class="list-group-item list-group-item-action mb-2">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h5 class="mb-1"><?php echo htmlspecialchars($filaEspacio['nombre_espacio'], ENT_SUBSTITUTE); ?></h5>
                  <small>Capacidad: <?php echo (int)$filaEspacio['capacidad_espacio']; ?> · Disponibilidad: <?php echo htmlspecialchars($filaEspacio['disponibilidad_espacio'], ENT_SUBSTITUTE); ?></small>
                  <?php if (!empty($filaEspacio['historial_espacio'])): ?>
                    <p class="mb-1 mt-2"><?php echo nl2br(htmlspecialchars($filaEspacio['historial_espacio'], ENT_SUBSTITUTE)); ?></p>
                  <?php endif; ?>
                  <a href="index-recursos.php?espacio=<?php echo $filaEspacio['id_espacio']; ?>" class="me-2">
                    Recursos (<?php echo $filaEspacio['cantidad_recursos']; ?>)
                  </a>
                </div>

                <div class="text-end">
  <button class="btn btn-sm btn-warning mb-1"
          data-bs-toggle="modal" data-bs-target="#modalEspacio"
          onclick='cargarEditarEspacio(
              <?php echo json_encode($filaEspacio['id_espacio']); ?>,
              <?php echo json_encode($filaEspacio['nombre_espacio']); ?>,
              <?php echo json_encode($filaEspacio['tipo']); ?>,
              <?php echo json_encode((int)$filaEspacio['capacidad_espacio']); ?>,
              <?php echo json_encode($filaEspacio['disponibilidad_espacio']); ?>,
              <?php echo json_encode($filaEspacio['historial_espacio']); ?>
          )'>
    Editar
  </button>


                  <form id="formEliminarEspacio<?php echo $filaEspacio['id_espacio']; ?>" style="display:inline;">
                    <button type="button" class="btn btn-sm btn-danger eliminar-espacio-boton" data-id="<?php echo $filaEspacio['id_espacio']; ?>">
                      Eliminar
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div> -->

        <!-- Modal Insertar / Editar Espacio 
        <div class="modal fade" id="modalEspacio" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Gestión de espacio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <form id="formularioEspacio" method="POST">
                <div class="modal-body">
                  <input type="hidden" id="accion" name="accion">
                  <input type="hidden" id="id_espacio" name="id_espacio">

                  <div class="mb-3">
                    <label>Nombre del espacio</label>
                    <input type="text" id="nombre_espacio" name="nombre_espacio" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Tipo de espacio</label>
                    <select id="tipo_espacio" name="tipo_espacio" class="form-control" required>
                      <option value="">Seleccione...</option>
                      <option value="Salón">Salón</option>
                      <option value="Aula">Aula</option>
                      <option value="Laboratorio">Laboratorio</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Capacidad</label>
                    <input type="number" id="capacidad_espacio" name="capacidad_espacio" class="form-control" required min="1" max="500">
                  </div>

                  <div class="mb-3">
                    <label>Disponibilidad</label>
                    <select id="disponibilidad_espacio" name="disponibilidad_espacio" class="form-control" required>
                      <option value="">Seleccione...</option>
                      <?php foreach($valoresDisponibilidad as $valorDisponibilidad): ?>
                        <option value="<?php echo htmlspecialchars($valorDisponibilidad, ENT_QUOTES); ?>"><?php echo htmlspecialchars($valorDisponibilidad, ENT_SUBSTITUTE); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Historial / Observaciones</label>
                    <textarea id="historial_espacio" name="historial_espacio" class="form-control" rows="3"></textarea>
                  </div>

                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              </form>

            </div>
          </div>
        </div>-->
