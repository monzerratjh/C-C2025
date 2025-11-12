<?php
include('./../../../conexion.php');
$con = conectar_bd();
session_start();
header("Content-Type: application/json; charset=UTF-8");

$accion = strtolower(trim($_POST['accion'] ?? ''));
if ($accion === 'insertar') $accion = 'crear';
if ($accion === 'borrar') $accion = 'eliminar';

// ------------------ Helpers ------------------

// Esta función obtiene todos los valores permitidos del campo "tipo_espacio" en la base de datos.
// Ese campo es un ENUM, por lo que solo puede tener ciertos valores (ej: Aula, Laboratorio, etc).
// Lo que hacemos es consultar la estructura de la tabla, extraer esos valores y devolverlos como un array.
// Así podemos validar que el tipo enviado por el usuario sea uno de los permitidos.
function tiposValidos(mysqli $con): array {
  $res = $con->query("SHOW COLUMNS FROM espacio LIKE 'tipo_espacio'");
  if (!$res) return [];
  preg_match_all("/'([^']+)'/", $res->fetch_assoc()['Type'], $out);
  return $out[1] ?? [];
}

//q variable que guarda la consulta preparada
function existeNombre(mysqli $con, string $nombre, ?int $excluirId = null): bool {
  if ($excluirId) {
    $q = $con->prepare("SELECT COUNT(*) FROM espacio WHERE nombre_espacio = ? AND id_espacio <> ?");
    $q->bind_param("si", $nombre, $excluirId);
  } else {
    $q = $con->prepare("SELECT COUNT(*) FROM espacio WHERE nombre_espacio = ?");
    $q->bind_param("s", $nombre);
  }
  $q->execute();
  $q->bind_result($c); $q->fetch(); $q->close();
  return $c > 0;
}

function validarNombre(string $nombre): bool {
  return (bool)preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 \-_]+$/u', $nombre);
}
function json_ok($msg, $extra = []) {
  echo json_encode(array_merge(["type"=>"success","message"=>$msg], $extra)); exit;
}
function json_err($msg) {
  echo json_encode(["type"=>"error","message"=>$msg]); exit;
}

try {
  $tipos = tiposValidos($con);

  // ================== CREAR ==================
  if ($accion === 'crear') {
    $nombre = trim($_POST['nombre_espacio'] ?? ''); //quita espacios al inicio/final y evita null.
    $cap = (int)($_POST['capacidad_espacio'] ?? 0); //fuerza entero (si viene vacío será 0).
    $hist = $_POST['historial_espacio'] ?? ''; //acepta texto libre.
    $tipo = $_POST['tipo_espacio'] ?? ''; //toma el tipo.

    if ($nombre === '' || !validarNombre($nombre)) json_err("Nombre inválido.");
    if (existeNombre($con, $nombre)) json_err("El nombre '$nombre' ya existe.");
    if ($cap < 1 || $cap > 100) json_err("Capacidad inválida (1-100).");
    if (!in_array($tipo, $tipos)) json_err("Tipo de espacio inválido.");

    // --- Imagen (opcional) ---
    $id_imagen = null;
    if (isset($_FILES['imagen_espacio']) && $_FILES['imagen_espacio']['error'] === UPLOAD_ERR_OK) {
      $nombreOriginal = $_FILES['imagen_espacio']['name'];
      $tmp = $_FILES['imagen_espacio']['tmp_name'];
      $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
      $nombreUnico = uniqid() . '.' . $ext;
      $destino = __DIR__ . '/../../../uploads/' . $nombreUnico;

      if (!file_exists(dirname($destino))) mkdir(dirname($destino), 0777, true);

      if (move_uploaded_file($tmp, $destino)) {
        $stmt = $con->prepare("INSERT INTO imagenes (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombreUnico);
        $stmt->execute();
        $id_imagen = $stmt->insert_id;
      }
    }

    // Crear el espacio
    $q = $con->prepare("INSERT INTO espacio (nombre_espacio, capacidad_espacio, historial_espacio, tipo_espacio, id_imagen)
                        VALUES (?,?,?,?,?)");
    $q->bind_param("sissi", $nombre, $cap, $hist, $tipo, $id_imagen);
    if (!$q->execute()) json_err("Error al crear espacio: ".$con->error);
    json_ok("Espacio creado.", ["id_espacio" => $q->insert_id]);
  }

  // ================== ATRIBUTOS ==================
  if ($accion === 'atributos') {
    $id = (int)($_POST['id_espacio'] ?? 0);
    if ($id <= 0) json_err("ID de espacio no recibido.");

    $ch = $con->prepare("SELECT COUNT(*) FROM espacio WHERE id_espacio=?");
    $ch->bind_param("i", $id);
    $ch->execute(); $ch->bind_result($existe); $ch->fetch(); $ch->close();
    if (!$existe) json_err("El espacio especificado no existe.");

    $map = [
      'Mesas' => (int)($_POST['mesas'] ?? 0),
      'Sillas' => (int)($_POST['sillas'] ?? 0),
      'Proyector' => (int)($_POST['proyector'] ?? 0),
      'Televisor' => (int)($_POST['televisor'] ?? 0),
      'Aire Acondicionado' => (int)($_POST['aire_acondicionado'] ?? 0),
      'Computadora de escritorio' => (int)($_POST['computadora_de_escritorio'] ?? 0),
      'Enchufes' => (int)($_POST['enchufes'] ?? 0),
      'Ventilador' => (int)($_POST['ventilador'] ?? 0)
    ];

    // Limpiar anteriores
    $del = $con->prepare("DELETE FROM espacio_atributo WHERE id_espacio = ?");
    $del->bind_param("i", $id);
    $del->execute();
    $del->close();

    // Insertar nuevos
    $ins = $con->prepare("INSERT INTO espacio_atributo (id_espacio, nombre_atributo, cantidad_atributo, descripcion_otro)
                          VALUES (?, ?, ?, NULL)");

    foreach ($map as $nombre => $cantidad) {
      if ($cantidad > 0) {
        $ins->bind_param("isi", $id, $nombre, $cantidad);
        $ins->execute();
      }
    }
    $ins->close();

    // Guardar campo "Otro"
    if (!empty($_POST['otro_descripcion']) && (int)$_POST['otro_cantidad'] > 0) {
      $desc = trim($_POST['otro_descripcion']);
      $cant = (int)$_POST['otro_cantidad'];
      $stmt = $con->prepare("INSERT INTO espacio_atributo (id_espacio, nombre_atributo, cantidad_atributo, descripcion_otro)
                             VALUES (?, 'Otro', ?, ?)");
      $stmt->bind_param("iis", $id, $cant, $desc);
      $stmt->execute();
      $stmt->close();
    }

    json_ok("Atributos guardados correctamente.");
  }

  // ================== EDITAR ==================
  if ($accion === 'editar') {
    $id = (int)($_POST['id_espacio'] ?? 0);
    $nombre = trim($_POST['nombre_espacio'] ?? '');
    $cap = (int)($_POST['capacidad_espacio'] ?? 0);
    $hist = $_POST['historial_espacio'] ?? '';
    $tipo = $_POST['tipo_espacio'] ?? '';

    if ($id <= 0) json_err("Falta el ID del espacio.");
    if ($nombre === '' || !validarNombre($nombre)) json_err("Nombre duplicado o inválido.");
    if (existeNombre($con, $nombre, $id)) json_err("Ya existe otro espacio con ese nombre.");
    if ($cap < 1 || $cap > 100) json_err("Capacidad inválida (1-100).");
    if (!in_array($tipo, $tipos)) json_err("Tipo de espacio inválido.");

    // --- Imagen (si se sube una nueva) ---
    $id_imagen = null;
    if (isset($_FILES['imagen_espacio']) && $_FILES['imagen_espacio']['error'] === UPLOAD_ERR_OK) {
      $nombreOriginal = $_FILES['imagen_espacio']['name'];
      $tmp = $_FILES['imagen_espacio']['tmp_name'];
      $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
      $nombreUnico = uniqid() . '.' . $ext;
      $destino = __DIR__ . '/../../../uploads/' . $nombreUnico;

      if (!file_exists(dirname($destino))) mkdir(dirname($destino), 0777, true);

      if (move_uploaded_file($tmp, $destino)) {
        $stmt = $con->prepare("INSERT INTO imagenes (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombreUnico);
        $stmt->execute();
        $id_imagen = $stmt->insert_id;
      }
    }

    if ($id_imagen) {
      $sql = "UPDATE espacio SET nombre_espacio=?, capacidad_espacio=?, historial_espacio=?, tipo_espacio=?, id_imagen=? WHERE id_espacio=?";
      $q = $con->prepare($sql);
      $q->bind_param("sissii", $nombre, $cap, $hist, $tipo, $id_imagen, $id);
    } else {
      $sql = "UPDATE espacio SET nombre_espacio=?, capacidad_espacio=?, historial_espacio=?, tipo_espacio=? WHERE id_espacio=?";
      $q = $con->prepare($sql);
      $q->bind_param("sissi", $nombre, $cap, $hist, $tipo, $id);
    }

    if (!$q->execute()) json_err("Error al actualizar: ".$q->error);
    json_ok("Espacio actualizado correctamente.");
  }

  // ================== ELIMINAR ==================
  if ($accion === 'eliminar') {
    $id = (int)($_POST['id_espacio'] ?? 0);
    if ($id <= 0) json_err("Falta el ID del espacio.");

    $q = $con->prepare("DELETE FROM espacio WHERE id_espacio=?");
    $q->bind_param("i", $id);
    if (!$q->execute()) json_err("No se pudo eliminar: ".$con->error);

    json_ok("Espacio eliminado.");
  }

  json_err("Acción desconocida.");
} catch(Throwable $e) {
  json_err("Error interno: ".$e->getMessage());
}
?>