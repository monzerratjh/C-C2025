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
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tercero MD horarios</title>
  
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
      <a href="./../../adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
    </div>
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
          <a href="../hora/asignar-hora.php">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../../img/logo.png" alt="Logo" class="logo"> 
    
      <h2>Cargar horarios <?= htmlspecialchars($grupo['nombre_grupo']) ?></h2>

      <table class="tabla-reserva">
        <thead>
          <tr>
            <th>Horario de entrada</th>
            <th>Horario de salida</th>
            <th>Materia</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>10:15</td>
            <td>11:05</td>
            <td>Programación</td>
            <td><i class="bi bi-pencil"></i></td>
            <td><i class="bi bi-trash"></i></td>
          </tr>
          <tr>
            <td>10:15</td>
            <td>11:05</td>
            <td>Programación</td>
            <td><i class="bi bi-pencil"></i></td>
            <td><i class="bi bi-trash"></i></td>
          </tr>
          <tr>
            <td>10:15</td>
            <td>11:05</td>
            <td>Inglés</td>
            <td><i class="bi bi-pencil"></i></td>
            <td><i class="bi bi-trash"></i></td>
          </tr>
          <tr>
            <td></td>
            <td><h2>+</h2></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="redireccionar-grupo.js"></script>
</body>
</html>
