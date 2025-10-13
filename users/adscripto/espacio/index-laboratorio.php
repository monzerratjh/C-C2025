<?php 
//include('../../../encabezado.php');
include('../../../conexion.php');
$con = conectar_bd();

// Obtener valores del ENUM 'disponibilidad_espacio' para el select
$enumConsulta = $con->query("SHOW COLUMNS FROM espacio LIKE 'disponibilidad_espacio'");
$filaEnum = $enumConsulta->fetch_assoc();
preg_match_all("/'([^']+)'/", $filaEnum['Type'], $coincidenciasEnum);
$valoresDisponibilidad = $coincidenciasEnum[1];

// Obtener solo los espacios de tipo 'Laboratorio'
$resultadoEspacios = $con->query("SELECT * FROM espacio WHERE tipo = 'Laboratorio' ORDER BY nombre_espacio");

$con->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorios</title>
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
      <a href="adscripto-espacio.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
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
            <a href="adscripto-espacio.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="adscripto-espacio.php">Volver</a>
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

    <img src="/img/logo.png" alt="Logo" class="logo"> 

    <div class="container my-4 espacio-contenedor">
    <h2>Laboratorios</h2>

  <div class="row justify-content-center mt-4">
    
            <?php while($espacio = $resultadoEspacios->fetch_assoc()): ?>
            <div class="col-6 mb-4">
                <div class="espacio-card">
                    <div class="espacio-cuerpo"></div>
                    <div class="espacio-footer d-flex justify-content-between align-items-center">
                        <button class="btn btn-sm btn-light"
                                data-bs-toggle="modal" data-bs-target="#modalEspacio"
                                          onclick="cargarEditarEspacio(
                                            '<?php echo $espacio['id_espacio']; ?>',
                                            '<?php echo htmlspecialchars($espacio['nombre_espacio'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($espacio['tipo'], ENT_QUOTES); ?>',
                                            '<?php echo (int)$espacio['capacidad_espacio']; ?>',
                                            '<?php echo htmlspecialchars($espacio['disponibilidad_espacio'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($espacio['historial_espacio'], ENT_QUOTES); ?>'
                                        )">
                                    <i class="bi bi-pencil-square"></i>
                            <!--<a href="editar-propiedad-espacio.php?id=<?=$espacio['id_espacio']?>"><i class="bi bi-pencil-square"></i></a>-->
                        </button>


                        <span><?=htmlspecialchars($espacio['nombre_espacio'])?></span>
                        

                        <button type="button" class="btn btn-sm btn-light btn-danger eliminar-espacio-boton" data-id="<?= $espacio['id_espacio'] ?>">
                          <i class="bi bi-trash"></i>
                        </button>


                    </div>
                </div>
            </div>
            <?php endwhile; ?>

            <!-- Agregar nuevo -->
            <div class="col-6 mb-4">
                <div class="espacio-card espacio-agregar d-flex justify-content-center align-items-center">
                  <button data-bs-toggle="modal" data-bs-target="#modalEspacio" onclick="prepararNuevoEspacio()"><span class="espacio-plus">+</span></button>
              </div>
            </div>

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
    </div>
</main>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../js/espacio.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>