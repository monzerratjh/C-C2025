<?php
include('../../../../conexion.php');
$con = conectar_bd();

// Obtener id_grupo desde la URL (se pasa desde asignar-hora.php)
$id_grupo = $_GET['id_grupo'] ?? null;

// TEMPORAL
if (!$id_grupo) {
  echo "<div style='margin: 2rem; color: red;'> No se especificó ningún grupo. 
        <a href='../adscripto-curso.php'>Volver</a></div>";
  exit;
}

// Obtener datos del grupo seleccionado
$grupoInfo = mysqli_query($con, "SELECT nombre_grupo FROM grupo WHERE id_grupo = $id_grupo");
$grupo = mysqli_fetch_assoc($grupoInfo);

// Obtener horarios disponibles
$horarios = mysqli_query($con, "
  SELECT id_horario_clase, hora_inicio, hora_fin 
  FROM horario_clase 
  ORDER BY hora_inicio
");

// Obtener asignaturas y docentes asociados (desde GADA) para este grupo
$gada = mysqli_query($con, "
  SELECT 
    gada.id_gada,
    a.nombre_asignatura, 
    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) AS docente
  FROM grupo_asignatura_docente_aula gada
  JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
  JOIN docente d ON d.id_docente = gada.id_docente
  JOIN usuario u ON u.id_usuario = d.id_usuario
  WHERE gada.id_grupo = $id_grupo
  ORDER BY a.nombre_asignatura
");

// a.nombre_asignatura = asignatura.nomnbre_asignatura
//  JOIN usuario u ON u.id_usuario = d.id_usuario -> une las tablas docente y usuario para obtener el nombre completo del docente


// Horarios asignados actuales
$horarios_asignados = mysqli_query($con, "
  SELECT 
  ha.id_horario_asignado,
  hc.id_horario_clase,
  gada.id_gada,
  hc.hora_inicio,
  hc.hora_fin,
  a.nombre_asignatura,
  CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) AS docente,
  e.nombre_espacio AS espacio,
  ha.dia

  FROM horario_asignado ha
  JOIN horario_clase hc ON hc.id_horario_clase = ha.id_horario_clase
  JOIN grupo_asignatura_docente_aula gada ON gada.id_gada = ha.id_gada
  JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
  JOIN docente d ON d.id_docente = gada.id_docente
  JOIN usuario u ON u.id_usuario = d.id_usuario
  LEFT JOIN espacio e ON gada.id_espacio = e.id_espacio
  WHERE gada.id_grupo = $id_grupo
  ORDER BY FIELD(ha.dia, 'Lunes','Martes','Miércoles','Jueves','Viernes'), hc.hora_inicio
");

// Obtener ENUM de días
$enum_dias = [];
$enum_query = mysqli_query($con, "SHOW COLUMNS FROM horario_asignado LIKE 'dia'");
if ($enum_query) {
  $row = mysqli_fetch_assoc($enum_query);
  preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
  $enum_dias = array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel adscripto</title>
  
  <!-- Bootstrap CSS + Iconos + letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  
  <!-- CSS propio -->
  <link rel="stylesheet" href="./../../../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="./../../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="../hora/asignar-hora.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../../adscripto-curso.php" class="fw-semibold seleccionado mb-2" data-i18n="courseManagement">Gestión de cursos</a>
    </div>

     <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
  </div>

  <!-- Contenedor principal con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../hora/asignar-hora.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="../hora/asignar-hora.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../adscripto-curso.php" class="fw-semibold seleccionado mb-2" data-i18n="courseManagement">Gestión de cursos</a>
    
       <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
    
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../../img/logo.png" alt="Logo" class="logo"> 
  
      <div class="acordion-total">
        <div class="acordion">

        
      <h2 data-i18n="uploadSchedules">Cargar horarios</h2><h2><?= htmlspecialchars($grupo['nombre_grupo']) ?> </h2>

      <br><br>

        <button class="boton-opciones2 agregar colorfondoverde" data-bs-toggle="modal" data-bs-target="#modalHorario">
          <h4>+</h4>
        </button>


        <?php
          // Agrupar los horarios por día
          $horariosPorDia = [];
          mysqli_data_seek($horarios_asignados, 0);
          while ($h = mysqli_fetch_assoc($horarios_asignados)) {
            $horariosPorDia[$h['dia']][] = $h;
          }

          foreach ($enum_dias as $dia):
            $lista = $horariosPorDia[$dia] ?? [];
        ?>

          <div class="dia">
           <button class=" toggle-dia boton-opciones  colorletrablanco jueves" ><?= $dia ?></button>
            <div class="contenido-dia">
              <?php if ($lista): ?>
                <table class="tabla-reserva">
                  <tr>
                    <th data-i18n="startTime">Horario entrada</th>
                    <th data-i18n="endTime">Horario salida</th>
                    <th data-i18n="subjectTeacher">Asignatura (Docente)</th>
                    <th data-i18n="facility">Espacio</th>
                    <th></th>
                </tr>
              
                <tbody>
                <?php foreach ($lista as $fila): ?>
                  <tr>
                    <td><?= substr($fila['hora_inicio'], 0, 5) ?></td>
                    <td><?= substr($fila['hora_fin'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_asignatura']) ?> (<?= htmlspecialchars($fila['docente']) ?>)</td>
                    <td><?= htmlspecialchars($fila['espacio'] ?? '-') ?></td>
                    <td>
                      <!-- BOTÓN EDITAR -->
                      <button type="button" class="btn btn-sm editar-btn"
                              data-id="<?= $fila['id_horario_asignado'] ?>"
                              data-dia="<?= htmlspecialchars($fila['dia']) ?>"
                              data-horario="<?= htmlspecialchars($fila['id_horario_clase']) ?>"
                              data-gada="<?= htmlspecialchars($fila['id_gada']) ?>"
                              data-bs-toggle="modal" data-bs-target="#modalHorario">
                        <i class="bi bi-pencil-square"></i>
                      </button>

                      <!-- BOTÓN ELIMINAR -->
                      <form action="cargar-hora-accion.php" method="POST" style="display:inline;">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_grupo" value="<?= $id_grupo ?>">
                        <input type="hidden" name="id_horario_asignado" value="<?= $fila['id_horario_asignado'] ?>">
                        <button type="button" class="btn btn-sm btn-danger eliminar-btn"
                                data-id="<?= $fila['id_horario_asignado'] ?>"
                                data-grupo="<?= $id_grupo ?>">
                          <i class="bi bi-trash"></i>
                        </button>

                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p data-i18n="noClasses">Sin clases cargadas</p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalHorario" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="cargar-hora-accion.php">

          <input type="hidden" name="id_grupo" value="<?= $id_grupo ?>">

            <div class="modal-header">
              <h5 class="modal-title" data-i18n="addEditSchedule">Agregar / Editar horario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <input type="hidden" name="accion" id="accionForm" value="insertar">
              <input type="hidden" name="id_horario_asignado" id="idHorarioAsignado">

              <div class="mb-3">
                <label class="form-label" data-i18n="day">Día</label>
                <select name="dia" id="dia" class="form-select" required>
                  <option value="" data-i18n="select">Seleccione...</option>
                  <?php foreach ($enum_dias as $d): ?>
                    <option value="<?= $d ?>"><?= $d ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label" data-i18n="schedule">Horario</label>
                <select name="id_horario_clase" id="id_horario_clase" class="form-select" required>
                  <option value="" data-i18n="select">Seleccione...</option>
                  <?php
                  mysqli_data_seek($horarios, 0);
                  while ($h = mysqli_fetch_assoc($horarios)): ?>
                    <option value="<?= $h['id_horario_clase'] ?>">
                      <?= substr($h['hora_inicio'], 0, 5) ?> - <?= substr($h['hora_fin'], 0, 5) ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label" data-i18n="subjectAndTeacher">Asignatura y docente</label>
                <select name="id_gada" id="id_gada" class="form-select" required>
                  <option value="" data-i18n="select">Seleccione...</option>
                  <?php
                  mysqli_data_seek($gada, 0);
                  while ($g = mysqli_fetch_assoc($gada)): ?>
                    <option value="<?= $g['id_gada'] ?>">
                      <?= $g['nombre_asignatura'] ?> (<?= $g['docente'] ?>)
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="cancel">Cancelar</button>
              <button type="submit" class="btn btn-success" data-i18n="save">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="./../../../../utils/form-log-in.js"></script> 

                    
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="/utils/translate.js"></script>
  
  <script src="./../../js/hora.js"></script>
  <script src="./../../../utils/desplegar-acordeon.js"></script>  
</body>
</html>