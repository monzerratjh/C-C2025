<?php 
include ('./../../../conexion.php');
$con = conectar_bd();

// Obtener id_grupo desde la URL (se pasa desde asignar-hora.php)
$id_grupo = $_GET['id_grupo'] ?? null;

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


// --


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
    <title>Panel estudiante</title>
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
          <h2 class="titulo-horario"><?= htmlspecialchars($grupo['nombre_grupo']) ?></h2>


          <!-- DÍAS DE LA SEMANA -->
          <?php
            // Agrupar los horarios por día
            $horariosPorDia = [];
            mysqli_data_seek($horarios_asignados, 0);
            while ($h = mysqli_fetch_assoc($horarios_asignados)) {
              $horariosPorDia[$h['dia']][] = $h;
            }

            // Mapeo de nombre de clase y texto visible
            $dias_clases = [
              'Lunes' => ['clase' => 'lunes', 'i18n' => 'monday'],
              'Martes' => ['clase' => 'martes', 'i18n' => 'tuesday'],
              'Miércoles' => ['clase' => 'miercoles', 'i18n' => 'wednesday'],
              'Jueves' => ['clase' => 'jueves', 'i18n' => 'thursday'],
              'Viernes' => ['clase' => 'viernes', 'i18n' => 'friday']
            ];

            foreach ($enum_dias as $dia):
              $lista = $horariosPorDia[$dia] ?? [];
              $claseDia = $dias_clases[$dia]['clase'];
              $i18nDia = $dias_clases[$dia]['i18n'];
            ?>

          <div class="dia">
           <button class=" toggle-dia boton-opciones colorletrablanco <?= $claseDia ?>" data-i18n="<?= $i18nDia ?>">
              <?= $dia ?>
           </button>
           <div class="contenido-dia">
              <?php if ($lista): ?>
                <table class="tabla-horario">
                  <tr>
                    <th data-i18n="startTime">Hora entrada</th>
                    <th data-i18n="endTime">Hora salida</th>
                    <th data-i18n="subject"> Asignatura </th>
                    <th data-i18n="facility"> Espacio </th>
                </tr>
              
                <tbody>
                <?php foreach ($lista as $fila): ?>
                  <tr>
                    <td><?= substr($fila['hora_inicio'], 0, 5) ?></td>
                    <td><?= substr($fila['hora_fin'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_asignatura']) ?> </td>
                    <td><?= htmlspecialchars($fila['espacio'] ?? '-') ?></td>
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