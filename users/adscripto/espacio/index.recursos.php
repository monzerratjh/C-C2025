<?php
// index-recursos.php
// Lista y permite gestionar recursos vinculados a un espacio específico.

include('../../../conexion.php');
$con = conectar_bd();
session_start();

// Validar que venga id_espacio por GET
$idEspacio = isset($_GET['espacio']) ? intval($_GET['espacio']) : null;
if (!$idEspacio) {
    die('ID de espacio inválido.');
}

// Obtener información del espacio
$stmtEspacio = $con->prepare("SELECT id_espacio, nombre_espacio FROM espacio WHERE id_espacio = ?");
$stmtEspacio->bind_param("i", $idEspacio);
$stmtEspacio->execute();
$resultadoEspacio = $stmtEspacio->get_result();
$filaEspacio = $resultadoEspacio->fetch_assoc();
$stmtEspacio->close();
if (!$filaEspacio) {
    die('Espacio no encontrado.');
}

// Obtener recursos asociados
$stmtRecursos = $con->prepare("SELECT recurso.id_recurso, recurso.nombre_recurso, recurso.tipo_recurso, recurso.estado_recurso, recurso.disponibilidad_recurso, recurso.historial_recurso FROM recurso WHERE recurso.id_espacio = ? ORDER BY recurso.nombre_recurso ASC");
$stmtRecursos->bind_param("i", $idEspacio);
$stmtRecursos->execute();
$resultadoRecursos = $stmtRecursos->get_result();

// Valores posibles para estado_recurso (ENUM)
$enumConsulta = $con->query("SHOW COLUMNS FROM recurso LIKE 'estado_recurso'");
$filaEnum = $enumConsulta->fetch_assoc();
preg_match_all("/'([^']+)'/", $filaEnum['Type'], $coincidenciasEnum);
$valoresEstadoRecurso = $coincidenciasEnum[1];

$con->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Recursos del espacio — <?php echo htmlspecialchars($filaEspacio['nombre_espacio'], ENT_SUBSTITUTE); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link rel="stylesheet" href="./../../../css/style.css"/>
</head>
<body>

  <div class="container my-4">
    <h2>Recursos — <?php echo htmlspecialchars($filaEspacio['nombre_espacio'], ENT_SUBSTITUTE); ?></h2>
    <div class="mb-3">
      <a href="adscripto-espacio.php" class="btn btn-secondary">Volver a espacios</a>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRecurso" onclick="prepararNuevoRecurso()">+ Nuevo recurso</button>
    </div>

    <div class="list-group">
      <?php while ($filaRecurso = $resultadoRecursos->fetch_assoc()): ?>
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1"><?php echo htmlspecialchars($filaRecurso['nombre_recurso'], ENT_SUBSTITUTE); ?></h5>
            <small>Tipo: <?php echo htmlspecialchars($filaRecurso['tipo_recurso'], ENT_SUBSTITUTE); ?> · Estado: <?php echo htmlspecialchars($filaRecurso['estado_recurso'], ENT_SUBSTITUTE); ?></small>
            <?php if (!empty($filaRecurso['historial_recurso'])): ?>
              <p class="mb-1 mt-2"><?php echo nl2br(htmlspecialchars($filaRecurso['historial_recurso'], ENT_SUBSTITUTE)); ?></p>
            <?php endif; ?>
          </div>
          <div class="text-end">
            <button class="btn btn-sm btn-warning mb-1"
                    data-bs-toggle="modal" data-bs-target="#modalRecurso"
                    onclick="cargarEditarRecurso(
                        '<?php echo $filaRecurso['id_recurso']; ?>',
                        '<?php echo htmlspecialchars($filaRecurso['nombre_recurso'], ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($filaRecurso['tipo_recurso'], ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($filaRecurso['disponibilidad_recurso'], ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($filaRecurso['estado_recurso'], ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($filaRecurso['historial_recurso'], ENT_QUOTES); ?>'
                    )">Editar</button>

            <button type="button" class="btn btn-sm btn-danger eliminar-recurso-boton" data-id="<?php echo $filaRecurso['id_recurso']; ?>">Eliminar</button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Modal Recurso -->
  <div class="modal fade" id="modalRecurso" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Gestión de recurso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formularioRecurso" method="POST">
          <div class="modal-body">
            <input type="hidden" id="accion_recurso" name="accion">
            <input type="hidden" id="id_recurso" name="id_recurso">
            <input type="hidden" id="id_espacio_form" name="id_espacio" value="<?php echo $idEspacio; ?>">

            <div class="mb-3">
              <label>Nombre del recurso</label>
              <input type="text" id="nombre_recurso" name="nombre_recurso" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Tipo de recurso</label>
              <input type="text" id="tipo_recurso" name="tipo_recurso" class="form-control" placeholder="Ej: Proyector, PC, Mesas" required>
            </div>

            <div class="mb-3">
              <label>Disponibilidad</label>
              <input type="text" id="disponibilidad_recurso" name="disponibilidad_recurso" class="form-control" placeholder="Ej: disponible, reservado" required>
            </div>

            <div class="mb-3">
              <label>Estado</label>
              <select id="estado_recurso" name="estado_recurso" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php foreach ($valoresEstadoRecurso as $valorEstado): ?>
                  <option value="<?php echo htmlspecialchars($valorEstado, ENT_QUOTES); ?>"><?php echo htmlspecialchars($valorEstado, ENT_SUBSTITUTE); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label>Historial / Observaciones</label>
              <textarea id="historial_recurso" name="historial_recurso" class="form-control" rows="3"></textarea>
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

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../js/recurso.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
