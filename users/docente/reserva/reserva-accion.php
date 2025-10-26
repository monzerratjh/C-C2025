<?php
// /users/docente/reservas/docente-reservar-accion.php
include('./../../../conexion.php');
session_start();
date_default_timezone_set('America/Montevideo');
header('Content-Type: application/json');

function json_ok($data = [], $extra = []) {
  http_response_code(200);
  echo json_encode(array_merge(["ok"=>true], $data, $extra));
  exit;
}

function json_err($msg, $code = 400) {
  http_response_code($code);
  echo json_encode(["ok"=>false, "msg"=>$msg]);
  exit;
}

$con = conectar_bd();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

try {
  // ------------------------------
  // Resolver id_docente desde sesión o por id_usuario
  // ------------------------------
  $id_docente = $_SESSION['id_docente'] ?? null;
  $id_usuario = $_SESSION['id_usuario'] ?? null;

  if (!$id_docente && $id_usuario) {
    $stmt = $con->prepare("SELECT id_docente FROM docente WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $doc = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($doc) {
      $id_docente = (int)$doc['id_docente'];
      $_SESSION['id_docente'] = $id_docente;
    }
  }
  if (!$id_docente) json_err("No se pudo determinar el docente autenticado.", 401);

  // ------------------------------
  // Cargar grupos (con espacio base)
  // ------------------------------
  if ($accion === 'cargar_grupos') {
    $stmt = $con->prepare("
      SELECT g.id_grupo, g.nombre_grupo, a.nombre_asignatura, e.nombre_espacio AS espacio_base
      FROM grupo_asignatura_docente_aula gada
      JOIN grupo g ON g.id_grupo = gada.id_grupo
      JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
      JOIN espacio e ON e.id_espacio = gada.id_espacio
      WHERE gada.id_docente = ?
      ORDER BY g.nombre_grupo, a.nombre_asignatura
    ");
    $stmt->bind_param("i", $id_docente);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $grupos = array_map(function($r){
      return [
        "id_grupo" => (int)$r['id_grupo'],
        "label"    => "{$r['nombre_grupo']} - {$r['nombre_asignatura']} ({$r['espacio_base']})"
      ];
    }, $res ?: []);

    json_ok(["data"=>$grupos]);
  }

  // ------------------------------
  // Cargar horas del día elegido para ese grupo
  // ------------------------------
  if ($accion === 'cargar_horas') {
    $id_grupo = intval($_GET['id_grupo'] ?? 0);
    $dia      = $_GET['dia'] ?? '';
    if (!$id_grupo || !$dia) json_err("Parámetros inválidos.");

    $stmt = $con->prepare("
      SELECT hc.id_horario_clase, hc.hora_inicio, hc.hora_fin
      FROM horario_asignado ha
      JOIN horario_clase hc ON hc.id_horario_clase = ha.id_horario_clase
      JOIN grupo_asignatura_docente_aula gada ON gada.id_gada = ha.id_gada
      WHERE gada.id_docente = ? AND gada.id_grupo = ? AND ha.dia = ?
      ORDER BY hc.hora_inicio
    ");
    $stmt->bind_param("iis", $id_docente, $id_grupo, $dia);
    $stmt->execute();
    $horas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    json_ok(["data"=>$horas ?: []]);
  }

  // ------------------------------
  // Cargar espacios libres
  // ------------------------------
  if ($accion === 'cargar_espacios') {
    $q = $con->query("
      SELECT id_espacio, nombre_espacio, tipo_espacio, capacidad_espacio
      FROM espacio
      WHERE disponibilidad_espacio = 'Libre'
      ORDER BY tipo_espacio, nombre_espacio
    ");
    $espacios = $q->fetch_all(MYSQLI_ASSOC);
    json_ok(["data"=>$espacios ?: []]);
  }

  // ------------------------------
  // Crear reserva
  // ------------------------------
  if ($accion === 'crear') {
    $id_grupo       = intval($_POST['id_grupo'] ?? 0);
    $fecha_reserva  = $_POST['fecha_reserva'] ?? '';
    $dia            = $_POST['dia'] ?? '';
    $cantidad_horas = intval($_POST['cantidad_horas'] ?? 0);
    $ids_horario    = $_POST['ids_horario'] ?? [];
    $id_espacio     = intval($_POST['id_espacio'] ?? 0);
    $observacion    = trim($_POST['observacion'] ?? '');

    if (!$id_grupo || !$dia || !$fecha_reserva || !$cantidad_horas || !$id_espacio || !is_array($ids_horario))
      json_err("Datos incompletos para la reserva.");

    // Validar fecha (se permite hoy, pero no horas ya pasadas)
    $hoy         = date('Y-m-d');
    $hora_actual = date('H:i:s');
    if ($fecha_reserva < $hoy) json_err("No se puede reservar un día que ya pasó.");

    if ($fecha_reserva === $hoy) {
      foreach ($ids_horario as $id_hc) {
        $stmtHora = $con->prepare("SELECT hora_inicio FROM horario_clase WHERE id_horario_clase = ?");
        $stmtHora->bind_param("i", $id_hc);
        $stmtHora->execute();
        $hora_row = $stmtHora->get_result()->fetch_assoc();
        $stmtHora->close();
        if ($hora_row && $hora_row['hora_inicio'] <= $hora_actual) {
          json_err("No se puede reservar una hora que ya pasó el día de hoy.");
        }
      }
    }

    // Validar que el docente realmente dicta ese día en ese grupo
    $stmt = $con->prepare("
      SELECT COUNT(*) AS total
      FROM horario_asignado ha
      JOIN grupo_asignatura_docente_aula gada ON ha.id_gada = gada.id_gada
      WHERE gada.id_docente = ? AND gada.id_grupo = ? AND ha.dia = ?
    ");
    $stmt->bind_param("iis", $id_docente, $id_grupo, $dia);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (intval($r['total']) === 0) json_err("No tienes clases asignadas ese día para este grupo.");

    // Obtener id_asignatura del vínculo
    $stmt = $con->prepare("
      SELECT id_asignatura, id_espacio AS id_espacio_base
      FROM grupo_asignatura_docente_aula
      WHERE id_docente = ? AND id_grupo = ?
      LIMIT 1
    ");
    $stmt->bind_param("ii", $id_docente, $id_grupo);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) json_err("No se encontró la asignatura asociada.");
    $id_asignatura        = (int)$row['id_asignatura'];
    $id_espacio_base      = (int)$row['id_espacio_base'];

    // Transacción
    $con->begin_transaction();

    foreach ($ids_horario as $id_hc) {
      $id_hc = (int)$id_hc;

      // Duplicado activo exacto (mismo docente, grupo, horario, fecha/día)
      $check = $con->prepare("
        SELECT COUNT(*) AS total
        FROM asignatura_docente_solicita_espacio
        WHERE id_docente = ? AND id_grupo = ? AND id_horario_clase = ?
          AND dia = ? AND fecha_reserva = ?
          AND estado_reserva IN ('Pendiente','Aceptada')
      ");
      $check->bind_param("iiiss", $id_docente, $id_grupo, $id_hc, $dia, $fecha_reserva);
      $check->execute();
      $dup = $check->get_result()->fetch_assoc();
      $check->close();
      if (intval($dup['total']) > 0) {
        $con->rollback();
        json_err("Ya existe una reserva activa para ese horario, día y fecha.");
      }

      // Conflicto por ESPACIO (cruce de horas con estado Pendiente/Aceptada)
      $ver = $con->prepare("
        SELECT COUNT(*) AS total
        FROM asignatura_docente_solicita_espacio adse
        JOIN horario_clase hc ON hc.id_horario_clase = adse.id_horario_clase
        WHERE adse.id_espacio = ?
          AND adse.fecha_reserva = ?
          AND adse.dia = ?
          AND adse.estado_reserva IN ('Pendiente','Aceptada')
          AND (
            hc.hora_inicio <= (SELECT hora_fin   FROM horario_clase WHERE id_horario_clase = ?)
            AND
            hc.hora_fin    >= (SELECT hora_inicio FROM horario_clase WHERE id_horario_clase = ?)
          )
      ");
      $ver->bind_param("issii", $id_espacio, $fecha_reserva, $dia, $id_hc, $id_hc);
      $ver->execute();
      $ocupado = $ver->get_result()->fetch_assoc();
      $ver->close();
      if (intval($ocupado['total']) > 0) {
        $con->rollback();
        json_err("El espacio seleccionado ya está reservado en ese horario.");
      }

      // Insertar
      $ins = $con->prepare("
        INSERT INTO asignatura_docente_solicita_espacio
        (id_asignatura, id_docente, id_grupo, id_horario_clase, id_espacio, dia, fecha_reserva, estado_reserva)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente')
      ");
      $ins->bind_param("iiiisss", $id_asignatura, $id_docente, $id_grupo, $id_hc, $id_espacio, $dia, $fecha_reserva);
      if (!$ins->execute()) {
        $ins->close();
        $con->rollback();
        json_err("Error al guardar la reserva.");
      }
      $ins->close();
    }

    $con->commit();

    // Nota: la disponibilidad del espacio se pone en Reservado al ser ACEPTADA por el adscripto.
    // Aquí solo guardamos la solicitud.

    json_ok(["msg"=>"Solicitud enviada. Queda en estado Pendiente hasta ser revisada."]);
  }

  // ------------------------------
  // Cancelar reserva
  // ------------------------------
  if ($accion === 'cancelar') {
    $id_horario_clase = intval($_POST['id_horario_clase'] ?? 0);
    $id_espacio       = intval($_POST['id_espacio'] ?? 0);
    if (!$id_horario_clase || !$id_espacio) json_err("Parámetros inválidos.");

    $stmt = $con->prepare("
      UPDATE asignatura_docente_solicita_espacio
      SET estado_reserva = 'Cancelada'
      WHERE id_docente = ? AND id_horario_clase = ? AND id_espacio = ?
        AND estado_reserva IN ('Pendiente','Aceptada')
    ");
    $stmt->bind_param("iii", $id_docente, $id_horario_clase, $id_espacio);
    $stmt->execute();
    $af = $stmt->affected_rows;
    $stmt->close();

    if ($af === 0) json_err("No se pudo cancelar (verifica estado actual).");

    // No tocamos disponibilidad del espacio aquí; la disponibilidad se maneja en el flujo del adscripto y en el autocierre de finalizadas.
    json_ok(["msg"=>"Reserva cancelada correctamente."]);
  }

  // ------------------------------
  // Listar reservas del docente (y actualizar finalizadas/liberar)
  // ------------------------------
  if ($accion === 'listar') {
    // Finalizar automáticamente reservas aceptadas que ya pasaron
    $con->query("
      UPDATE asignatura_docente_solicita_espacio adse
      JOIN horario_clase hc ON hc.id_horario_clase = adse.id_horario_clase
      SET adse.estado_reserva = 'Finalizada'
      WHERE adse.estado_reserva = 'Aceptada'
        AND (
          adse.fecha_reserva < CURDATE()
          OR (adse.fecha_reserva = CURDATE() AND hc.hora_fin < CURTIME())
        )
    ");

    // Liberar espacios de reservas finalizadas
    $con->query("
      UPDATE espacio e
      JOIN asignatura_docente_solicita_espacio adse ON adse.id_espacio = e.id_espacio
      SET e.disponibilidad_espacio = 'Libre'
      WHERE adse.estado_reserva = 'Finalizada'
    ");

    // Listado solo de hoy en adelante (si quieres histórico, quita el WHERE)
    $stmt = $con->prepare("
      SELECT 
        adse.id_asignatura, adse.id_docente, adse.id_grupo, adse.id_horario_clase, adse.id_espacio,
        adse.estado_reserva, adse.fecha_hora_reserva, adse.fecha_reserva, adse.dia,
        a.nombre_asignatura, e.nombre_espacio, hc.hora_inicio, hc.hora_fin, g.nombre_grupo
      FROM asignatura_docente_solicita_espacio adse
      JOIN asignatura a ON a.id_asignatura = adse.id_asignatura
      JOIN grupo g ON g.id_grupo = adse.id_grupo
      JOIN espacio e ON e.id_espacio = adse.id_espacio
      JOIN horario_clase hc ON hc.id_horario_clase = adse.id_horario_clase
      WHERE adse.id_docente = ?
        AND adse.fecha_reserva >= CURDATE()
      ORDER BY CAST(adse.fecha_reserva AS DATE) ASC, hc.hora_inicio ASC
    ");
    $stmt->bind_param("i", $id_docente);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    json_ok(["data"=>$rows ?: []]);
  }

  json_err("Acción no reconocida: $accion");

} catch (Throwable $e) {
  json_err($e->getMessage());
} finally {
  if ($con) $con->close();
}
