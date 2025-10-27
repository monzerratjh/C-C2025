<?php
session_start();
include('./../../../conexion.php');

// Verificar sesión
$id_secretario = $_SESSION['id_secretario'] ?? null;

$con = conectar_bd();

// Obtener horarios registrados
$resultadoHorarios = $con->query("
  SELECT id_horario_clase, hora_inicio, hora_fin, id_secretario
  FROM horario_clase
  ORDER BY hora_inicio ASC
");

$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Secretario</title>

  <!-- Bootstrap CSS + Iconos + letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./../../../css/style.css">
</head>

<body class="horarios-page">

  <!-- Menú hamburguesa (móviles) -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="./../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral móvil -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="./../secretario-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i><span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../usuario/secretario-usuario.php" class="nav-opciones" data-i18n="users">Usuarios</a>
      <a href="./../horario/horario-secretario.php" class="fw-semibold seleccionado" data-i18n="schedule" >Horarios</a>
      <a href="./../grupo/secretario-grupo.php" class="nav-opciones" data-i18n="groups">Grupos</a>
      <a href="./../recurso/secretario-recurso.php" class="nav-opciones" data-i18n="resources">Recursos</a>
   
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="./../secretario-bienvenida.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
         <a href="./../secretario-bienvenida.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../usuario/secretario-usuario.php" class="nav-opciones" data-i18n="users">Usuarios</a>
      <a href="./../horario/horario-secretario.php" class="fw-semibold seleccionado" data-i18n="schedule" >Horarios</a>
      <a href="./../grupo/secretario-grupo.php" class="nav-opciones" data-i18n="groups">Grupos</a>
      <a href="./../recurso/secretario-recurso.php" class="nav-opciones" data-i18n="resources">Recursos</a>
   
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../img/logo.png" alt="Logo" class="logo">

        <h2 data-i18n="schedules">Horarios</h2>
        <p data-i18n="recordSchedules">Registrá los horarios indicando la hora de inicio y finalización por hora</p>

      <table class="tabla-secretario horarios">
        <thead>
          <tr>
            <th data-i18n="startTime">Hora de inicio</th>
            <th data-i18n="endTime">Hora de finalización</th>
            <th></th>
            <th></th>
          </tr>
          
        </thead>
        <tbody>
          <?php while($filaHorario = $resultadoHorarios->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($filaHorario['hora_inicio']) ?></td>
            <td><?= htmlspecialchars($filaHorario['hora_fin']) ?></td>
           
            <td>
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalHorario"
                onclick="cargarEditarHorario(
                  '<?= $filaHorario['id_horario_clase'] ?>',
                  '<?= $filaHorario['hora_inicio'] ?>',
                  '<?= $filaHorario['hora_fin'] ?>'
                )">
                <i class="bi bi-pencil-square"></i>
              </button>
            </td>
            <td>
              <form id="formEliminar<?= $filaHorario['id_horario_clase']; ?>" method="POST" style="display:inline;">
                <input type="hidden" name="accionHorario" value="eliminar">
                <input type="hidden" name="id_horario_clase" value="<?= $filaHorario['id_horario_clase']; ?>">
                <button type="button" class="btn btn-sm btn-danger eliminar-horario-btn" data-id="<?= $filaHorario['id_horario_clase']; ?>">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          
          <?php endwhile; ?>

           <tr>
              <td colspan="7" class="text-center"> <!-- Une todas las columnas en una sola celda y centra el "+" -->
                <h4 class="agregar"
                    data-bs-toggle="modal"
                    data-bs-target="#modalHorario"
                    onclick="document.getElementById('accionHorario').value='insertar';">
                  +
                  </h4>
              </td>
          </tr>
         
        </tbody>
      </table>
    </main>
  </div>

  <!-- Modal agregar/editar horario -->
  <div class="modal fade" id="modalHorario" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" id="formHorario">

          <div class="modal-body">
            <input type="hidden" name="accionHorario" id="accionHorario">
            <input type="hidden" name="id_horario_clase" id="id_horario_clase">

            <div class="mb-3">
              <label data-i18n="startTime">Hora de Inicio</label>
              <input type="time" class="form-control" name="hora_inicio" id="hora_inicio" required>
            </div>

            <div class="mb-3">
              <label data-i18n="endTime">Hora de Fin</label>
              <input type="time" class="form-control" name="hora_fin" id="hora_fin" required>
            </div>

            <input type="hidden" name="id_secretario" value="<?= htmlspecialchars($id_secretario); ?>">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="close">Cerrar</button>
            <button type="submit" class="btn btn-primary" data-i18n="save">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../js/horario.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>

</body>
</html>
