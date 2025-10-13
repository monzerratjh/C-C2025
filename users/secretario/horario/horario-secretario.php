<?php
//include('../encabezado.php');
//include('./../../../conexion.php');


// Inicia la sesión para poder acceder a las variables globales $_SESSION
session_start(); 

// Obtiene el ID del secretario guardado en la sesión (cuando el usuario inició sesión)
// Si no existe (por ejemplo, si el usuario no es secretario o la sesión expiró),
// se asigna el valor null para evitar errores.
$id_secretario = $_SESSION['id_secretario'] ?? null;


$con = conectar_bd();

// Obtener todos los horarios registrados
$resultadoHorarios = $con->query("
                                  SELECT id_horario_clase, hora_inicio, hora_fin, id_secretario
                                  FROM horario_clase
                                  ORDER BY hora_inicio ASC");

$con->close(); // cierro conexión cuando ya tengo todos los datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Horarios - Secretario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./../../../css/style.css">
</head>
<body>

<!-- Menú hamburguesa para móviles -->
<nav class="d-md-none">
  <div class="container-fluid">
    <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
      <img class="menuResponsive" src="/img/menu.png" alt="menu">
    </button>
    <img class="logoResponsive" src="/img/logo.png" alt="logoResponsive">
  </div>
</nav>

<!-- Menú lateral (para celulares/tablets) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
  <div class="offcanvas-header">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <a href="../secretario-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
    <i class="bi bi-translate traductor-menu"></i>
    
    <a href="../usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
    <a href="../horario/horario-secretario.php" class="fw-semibold seleccionado mb-2">Horarios</a>
    <a href="../grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
  </div>
</div>

<!-- Contenedor general -->
<div class="container-fluid">
  <div class="row">

    <!-- Banner pantallas grandes -->
    <div class="col-md-3 barra-lateral d-none d-md-flex">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../secretario-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="../secretario-bienvenida.php">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="../usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
      <a href="../horario/horario-secretario.php" class="fw-semibold seleccionado mb-2">Horarios</a>
      <a href="../grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
    </div>

    <!-- Contenido principal -->
    <main class="col-md-9 principal">
      <img src="/img/logo.png" alt="Logo" class="logo"> 

      <div class="bloque-agregar">
        <button class="etiqueta">Horarios</button>
        <button class="agregar" data-bs-toggle="modal" data-bs-target="#modalHorario" onclick="document.getElementById('accionHorario').value='insertar';">
          +
        </button>
      </div>

      <table class="tabla-horarios-secretario">
        <thead>
          <tr>
            <th>Horario de inicio</th>
            <th>Horario de finalización</th>
            <th>Editar</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          <?php while($filaHorario = $resultadoHorarios->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($filaHorario['hora_inicio'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($filaHorario['hora_fin'], ENT_QUOTES) ?></td>
            <td>

          <!-- Botones Editar / Eliminar -->
            <div class="mt-2">  
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalHorario"
                onclick="cargarEditarHorario(
                  '<?php echo $filaHorario['id_horario_clase'] ?>',
                  '<?= $filaHorario['hora_inicio'] ?>',
                  '<?= $filaHorario['hora_fin'] ?>',
                )"><i class="bi bi-pencil"></i></button>
            </td> </div>

            <div class="mt-2">
            <td>
              <form id="formEliminar<?php echo $filaHorario['id_horario_clase'];?>" method="POST" style="display:inline;" >
                  <input type="hidden" name="accionHorario" value="eliminar">

                  <input type="hidden" name="id_horario_clase" value="<?php echo $filaHorario['id_horario_clase']; ?>">

                  <button type="button" 
                        class="btn btn-sm btn-danger eliminar-horario-btn" 
                        data-id="<?php echo $filaHorario['id_horario_clase'] ?>"><i class="bi bi-trash"></i>
                  </button>
              </form>
            </td></div>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
</div>

<!-- Modal para agregar/editar horario -->
<div class="modal fade" id="modalHorario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" id="formHorario">
        <div class="modal-header">

          <h5 class="modal-title">Gestión de Horario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="accionHorario" id="accionHorario">
          <input type="hidden" name="id_horario_clase" id="id_horario_clase">

         <!-- ADSCRIPTO:
          
         <div class="mb-3">
            <label>Día</label>
            <select class="form-control" name="dia" id="dia" required>
              <option value="">Seleccione...</option>
              <option value="Lunes">Lunes</option>
              <option value="Martes">Martes</option>
              <option value="Miércoles">Miércoles</option>
              <option value="Jueves">Jueves</option>
              <option value="Viernes">Viernes</option>
            </select>
          </div>                                                        -->

          <div class="mb-3">
            <label>Hora de Inicio</label>
            <input type="time" class="form-control" name="hora_inicio" id="hora_inicio" required>
          </div>

          <div class="mb-3">
            <label>Hora de Fin</label>
            <input type="time" class="form-control" name="hora_fin" id="hora_fin" required>
          </div>

          <div class="mb-3"> <!-- id del secretario asociado con el grupo creado para que se gusrade en la bd -->
            <input type="hidden" name="id_secretario" value="<?php echo htmlspecialchars($id_secretario); ?>">
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
        </div>
      </div>
    </div>
  </div>

   <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="../js/horario.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>