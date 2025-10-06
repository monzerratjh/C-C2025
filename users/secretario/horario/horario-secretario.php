<?php
//include('../encabezado.php');
include('../../../conexion.php');
$conexion = conectar_bd();
session_start();

// Obtener todos los horarios con información del grupo
$consulta_horarios = $conexion->query("
    SELECT horario_clase.id_horario, horario_clase.dia, horario_clase.hora_inicio, horario_clase.hora_fin,
           horario_clase.turno, horario_clase.id_grupo, grupo.nombre_grupo
    FROM horario_clase
    JOIN grupo ON horario_clase.id_grupo = grupo.id_grupo
");

// Obtener lista de grupos para el select
$consulta_grupos = $conexion->query("SELECT id_grupo, nombre_grupo FROM grupo");

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Horarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="container mt-4">
    <h1>Gestión de Horarios de Clase</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalGestionHorario" 
            onclick="document.getElementById('accionHorario').value='insertar';">
        Agregar Horario
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre del Grupo</th>
                <th>Día</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
                <th>Turno</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($registro_horario = $consulta_horarios->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($registro_horario['nombre_grupo'], ENT_QUOTES) ?></td>
                <td><?= $registro_horario['dia'] ?></td>
                <td><?= $registro_horario['hora_inicio'] ?></td>
                <td><?= $registro_horario['hora_fin'] ?></td>
                <td><?= $registro_horario['turno'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalGestionHorario"
                        onclick="cargarEditarHorario(
                            '<?= $registro_horario['id_horario'] ?>',
                            '<?= $registro_horario['id_grupo'] ?>',
                            '<?= $registro_horario['dia'] ?>',
                            '<?= $registro_horario['hora_inicio'] ?>',
                            '<?= $registro_horario['hora_fin'] ?>',
                            '<?= $registro_horario['turno'] ?>'
                        )">Editar</button>

                    <button class="btn btn-danger btn-sm eliminar-horario-btn" data-id="<?= $registro_horario['id_horario'] ?>">
                        Eliminar
                    </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar/editar horario -->
<div class="modal fade" id="modalGestionHorario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formularioGestionHorario">
      <div class="modal-header">
        <h5 class="modal-title">Gestión de Horario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="accion" id="accionHorario">
          <input type="hidden" name="id_horario" id="id_horario">

          <div class="mb-3">
              <label for="id_grupo">Grupo</label>
              <select class="form-control" name="id_grupo" id="id_grupo" required>
                  <option value="">Seleccione un grupo</option>
                  <?php while($grupo = $consulta_grupos->fetch_assoc()): ?>
                      <option value="<?= $grupo['id_grupo'] ?>"><?= htmlspecialchars($grupo['nombre_grupo'], ENT_QUOTES) ?></option>
                  <?php endwhile; ?>
              </select>
          </div>

          <div class="mb-3">
              <label for="dia">Día de la Semana</label>
              <select class="form-control" name="dia" id="dia" required>
                  <option value="">Seleccione un día</option>
                  <option value="Lunes">Lunes</option>
                  <option value="Martes">Martes</option>
                  <option value="Miércoles">Miércoles</option>
                  <option value="Jueves">Jueves</option>
                  <option value="Viernes">Viernes</option>
              </select>
          </div>

          <div class="mb-3">
              <label for="hora_inicio">Hora de Inicio</label>
              <input type="time" class="form-control" name="hora_inicio" id="hora_inicio" required>
          </div>

          <div class="mb-3">
              <label for="hora_fin">Hora de Fin</label>
              <input type="time" class="form-control" name="hora_fin" id="hora_fin" required>
          </div>

          <div class="mb-3">
              <label for="turno">Turno</label>
              <select class="form-control" name="turno" id="turno" required>
                  <option value="">Seleccione un turno</option>
                  <option value="Matutino">Matutino</option>
                  <option value="Vespertino">Vespertino</option>
                  <option value="Nocturno">Nocturno</option>
              </select>
          </div>

          <input type="hidden" name="id_secretario" value="<?= $_SESSION['id_secretario'] ?? 1 ?>">
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Horario</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/horario.js"></script>
<script src="../js/validation.js"></script>

</body>
</html>