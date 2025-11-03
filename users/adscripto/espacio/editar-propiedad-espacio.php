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

<!-- Menú hamburguesa -->
<nav class="d-md-none">
  <div class="container-fluid">
    <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
      <img class="menuResponsive" src="./../../../img/menu.png" alt="menu">
    </button>
    <img class="logoResponsive" src="./../../../img/logo.png" alt="logoRespnsive">
  </div>
</nav>

<!-- Menú lateral responsive -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
  <div class="offcanvas-header">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">

    <div class="banner-parte-superior">
      <a href="adscripto-espacio.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>
    </div>

      <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="./../reserva/reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="./../falta/falta-docente.php" class="nav-opciones mb-2">Falta docente</a>
      <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2">Gestión de cursos</a>

      <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
        <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
      </a>
  </div>
</div>

<div class="contenedor">

  <!-- BARRA LATERAL -->
  <aside class="barra-lateral d-none d-md-flex">
    <div class="volverGeneral">
      <div class="volver">
        <a href="adscripto-espacio.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
        <a href="adscripto-espacio.php">Volver</a>
      </div>
      <i class="bi bi-translate traductor-menu"></i>
    </div>

      <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="./../reserva/reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="./../falta/falta-docente.php" class="nav-opciones mb-2">Falta docente</a>
      <a href="./../curso/adscripto-curso.php" class="nav-opciones mb-2">Gestión de cursos</a>

      <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion">
        <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
      </a>
  </aside>

  <!-- CONTENIDO -->
  <main class="principal">
    <img src="./../../../img/logo.png" class="logo" alt="Logo">

    <div class="container my-4 espacio-contenedor">
      <h2><?= htmlspecialchars($espacio['nombre_espacio']) ?></h2>

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

        <?php if(isset($atributos['Otro'])): ?>
          <div class="row align-items-center mb-2">
            <div class="col-4 text-end">Otro (<?= htmlspecialchars($atributos['Otro']['descripcion']) ?>)</div>
            <div class="col-4"><input type="checkbox" checked disabled></div>
            <div class="col-4"><input type="number" value="<?= $atributos['Otro']['cantidad'] ?>" class="form-control form-control-sm bg-light border-0" disabled></div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="./../js/espacio.js"></script>
<script src="./../../../utils/translate.js"></script>

</body>
</html>
