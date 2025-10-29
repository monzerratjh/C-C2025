

<?php
include('./../../../conexion.php');
$con = conectar_bd();

$id_espacio = $_GET['id_espacio'] ?? null;
if (!$id_espacio) {
  echo "<script>alert('Error: Espacio no especificado.'); window.location.href='adscripto-espacio.php';</script>";
  exit;
}

$stmt = $con->prepare("SELECT * FROM espacio WHERE id_espacio = ?");
$stmt->bind_param("i", $id_espacio);
$stmt->execute();
$espacio = $stmt->get_result()->fetch_assoc();
if (!$espacio) {
  echo "<script>alert('No se encontró el espacio.'); window.location.href='adscripto-espacio.php';</script>";
  exit;
}

$atributos = [];
$q = $con->prepare("SELECT nombre_atributo, cantidad_atributo, descripcion_otro FROM espacio_atributo WHERE id_espacio = ?");
$q->bind_param("i", $id_espacio);
$q->execute();
$res = $q->get_result();
while ($r = $res->fetch_assoc()) {
  $atributos[$r['nombre_atributo']] = [
    'cantidad' => $r['cantidad_atributo'],
    'descripcion' => $r['descripcion_otro']
  ];
}
$con->close();

$attrs = ['Mesas','Sillas','Proyector','Televisor','Aire Acondicionado','Computadora de escritorio','Enchufes','Ventilador'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Espacio - <?= htmlspecialchars($espacio['nombre_espacio']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./../../../css/style.css">
  <style>
    .no-edit input, .no-edit textarea { background-color:#d9d9d9 !important; pointer-events:none; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <main class="col-md-9 mx-auto principal">
      <img src="./../../../img/logo.png" class="logo" alt="Logo">
      <div class="container my-4 espacio-contenedor">
        <div class="espacio-titulo text-center mb-4">
          <div class="espacio-cuerpo rounded-pill text-white fw-semibold py-2 px-4" 
               style="background-color:#4ec1b2;display:inline-block;">
            <?= htmlspecialchars($espacio['nombre_espacio']) ?>
          </div>
        </div>

        <form class="no-edit text-center">
          <div class="row justify-content-center mb-3">
            <div class="col-6 col-md-4 text-end fw-semibold">Capacidad:</div>
            <div class="col-6 col-md-4">
              <input type="number" value="<?= htmlspecialchars($espacio['capacidad_espacio']) ?>" class="form-control">
            </div>
          </div>

          <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-8">
              <textarea class="form-control" rows="3"><?= htmlspecialchars($espacio['historial_espacio']) ?></textarea>
            </div>
          </div>

          <hr><h5 class="mb-3">Atributos del espacio</h5>
          <?php foreach ($attrs as $attr):
            $cantidad = $atributos[$attr]['cantidad'] ?? '';
          ?>
            <div class="row align-items-center mb-2">
              <div class="col-4 text-end"><?= $attr ?></div>
              <div class="col-4"><input type="checkbox" class="form-check-input" <?= $cantidad ? 'checked' : '' ?> disabled></div>
              <div class="col-4"><input type="number" class="form-control form-control-sm bg-light border-0" value="<?= $cantidad ?>" disabled></div>
            </div>
          <?php endforeach; ?>

          <!-- Campo "Otro" -->
          <?php if(isset($atributos['Otro'])): ?>
            <div class="row align-items-center mb-2">
              <div class="col-4 text-end">Otro (<?= htmlspecialchars($atributos['Otro']['descripcion']) ?>)</div>
              <div class="col-4"><input type="checkbox" class="form-check-input" checked disabled></div>
              <div class="col-4"><input type="number" class="form-control form-control-sm bg-light border-0" value="<?= $atributos['Otro']['cantidad'] ?>" disabled></div>
            </div>
          <?php endif; ?>

          <div class="text-center mt-4">
            <button type="button" id="btnEditar" class="btn btn-success px-4">Editar</button>
            <button type="button" id="btnEliminar" class="btn btn-danger ms-3 px-4">Eliminar</button>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<!-- Modal edición atributos -->
<div class="modal fade" id="modalEditarAtributos" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5>Editar atributos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form id="formAtributos">
          <input type="hidden" name="id_espacio" value="<?= $id_espacio ?>">
          <input type="hidden" name="accion" value="atributos">
          <?php foreach ($attrs as $attr):
            $cantidad = $atributos[$attr]['cantidad'] ?? '';
            $checked = $cantidad ? 'checked' : '';
            $disabled = $cantidad ? '' : 'disabled';
            $id = strtolower(str_replace([' ','á','é','í','ó','ú'],['_','a','e','i','o','u'],$attr));
          ?>
            <div class="row align-items-center mb-2">
              <div class="col-4 text-end"><?= $attr ?></div>
              <div class="col-4"><input type="checkbox" class="form-check-input toggleCantidad" data-target="<?= $id ?>" <?= $checked ?>></div>
              <div class="col-4"><input type="number" name="<?= $id ?>" id="<?= $id ?>" value="<?= $cantidad ?>" class="form-control form-control-sm bg-light border-0" <?= $disabled ?>></div>
            </div>
          <?php endforeach; ?>
          <!-- Campo "Otro" -->
          <div class="row align-items-center mb-2">
            <div class="col-4 text-end">Otro (especificar)</div>
            <div class="col-4">
              <input type="checkbox" class="form-check-input toggleCantidad" data-target="otro_personalizado">
            </div>
            <div class="col-4">
              <input type="text" name="otro_descripcion" id="otro_descripcion" class="form-control form-control-sm mb-1" placeholder="Nombre del atributo" disabled>
              <input type="number" name="otro_cantidad" id="otro_personalizado" class="form-control form-control-sm bg-light border-0" placeholder="Cantidad" disabled>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success" id="guardarAtributosBtn">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="./../js/espacio.js"></script>
</body>
</html>
