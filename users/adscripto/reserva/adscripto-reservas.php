<?php
include('./../../../conexion.php');
session_start();
$con = conectar_bd();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  header('Location: ./../../../index.php');
  exit;
}

$nombre_adscripto = $_SESSION['nombre_usuario'] ?? '';
$apellido_adscripto = $_SESSION['apellido_usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de reservas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-3">Gestión de reservas de espacios</h2>
    <p>Adscripto: <strong><?= htmlspecialchars($nombre_adscripto . ' ' . $apellido_adscripto) ?></strong></p>

    <div class="card">
      <div class="card-header bg-primary text-white">
        <i class="bi bi-calendar-check"></i> Reservas pendientes
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped align-middle" id="tablaReservas">
            <thead class="table-light">
              <tr>
                <th>Docente</th>
                <th>Grupo</th>
                <th>Asignatura</th>
                <th>Espacio</th>
                <th>Día</th>
                <th>Fecha</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
                <th>Estado</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div id="sinReservas" class="text-muted">Cargando reservas...</div>
      </div>
    </div>
  </div>

  <script src="./../js/adscripto-reservas.js"></script>
</body>
</html>