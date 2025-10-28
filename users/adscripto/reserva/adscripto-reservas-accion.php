<?php
include('./../../../conexion.php');
session_start();
header('Content-Type: application/json');

$con = conectar_bd();
$accion = $_POST['accion'] ?? '';

try {
  $id_adscripto = $_SESSION['id_adscripto'] ?? null;
  if (!$id_adscripto) throw new Exception("No se pudo identificar el adscripto.");

// ------------------------------------------------------------
//  LISTAR TODAS LAS RESERVAS (con actualización automática de estado)
// ------------------------------------------------------------
if ($accion === 'listar') {
  //  Actualizar automáticamente las reservas vencidas (Finalizadas)
  $con->query("
    UPDATE asignatura_docente_solicita_espacio AS adse
    JOIN horario_clase hc ON hc.id_horario_clase = adse.id_horario_clase
    SET adse.estado_reserva = 'Finalizada'
    WHERE adse.estado_reserva = 'Aceptada'
      AND (
        adse.fecha_reserva < CURDATE()
        OR (adse.fecha_reserva = CURDATE() AND hc.hora_fin < CURTIME())
      )
  ");

  //  Liberar espacios de las reservas finalizadas
  $con->query("
    UPDATE espacio e
    JOIN asignatura_docente_solicita_espacio adse ON adse.id_espacio = e.id_espacio
    SET e.disponibilidad_espacio = 'Libre'
    WHERE adse.estado_reserva = 'Finalizada'
  ");

  // Listar todo actualizado
  $stmt = $con->query("
    SELECT 
      adse.id_reserva,
      adse.id_asignatura,
      adse.id_docente,
      adse.id_horario_clase,
      adse.id_espacio,
      adse.estado_reserva,
      adse.fecha_reserva,
      adse.dia,
      a.nombre_asignatura,
      e.nombre_espacio,
      hc.hora_inicio,
      hc.hora_fin,
      g.nombre_grupo,
      u.nombre_usuario AS nombre_docente,
      u.apellido_usuario AS apellido_docente
    FROM asignatura_docente_solicita_espacio adse
    JOIN asignatura a ON a.id_asignatura = adse.id_asignatura
    JOIN grupo g ON g.id_grupo = adse.id_grupo
    JOIN espacio e ON e.id_espacio = adse.id_espacio
    JOIN horario_clase hc ON hc.id_horario_clase = adse.id_horario_clase
    JOIN docente d ON d.id_docente = adse.id_docente
    JOIN usuario u ON u.id_usuario = d.id_usuario
    WHERE adse.fecha_reserva >= CURDATE()
ORDER BY CAST(adse.fecha_reserva AS DATE) ASC, hc.hora_inicio ASC

  ");

  echo json_encode(["ok" => true, "data" => $stmt->fetch_all(MYSQLI_ASSOC)]);
  exit;
}

// ------------------------------------------------------------
//   CAMBIAR ESTADO DE RESERVA (Aceptar o Rechazar)
// ------------------------------------------------------------
if ($accion === 'cambiar_estado') {
  $id_reserva = intval($_POST['id_reserva'] ?? 0);
  $nuevo_estado = $_POST['nuevo_estado'] ?? '';

  if (!$id_reserva || !in_array($nuevo_estado, ['Aceptada', 'Rechazada'])) {
    throw new Exception("Error: Datos inválidos para actualizar estado.");
  }

  // Obtener el id_espacio antes de actualizar, para modificar su disponibilidad
  $stmtEsp = $con->prepare("SELECT id_espacio FROM asignatura_docente_solicita_espacio WHERE id_reserva = ?");
  $stmtEsp->bind_param("i", $id_reserva);
  $stmtEsp->execute();
  $resEsp = $stmtEsp->get_result()->fetch_assoc();
  $stmtEsp->close();

  if (!$resEsp) throw new Exception("Reserva no encontrada.");
  $id_espacio = intval($resEsp['id_espacio']);

  // Actualizar el estado de la reserva
  $stmt = $con->prepare("UPDATE asignatura_docente_solicita_espacio SET estado_reserva = ? WHERE id_reserva = ?");
  $stmt->bind_param("si", $nuevo_estado, $id_reserva);
  $stmt->execute();
  $stmt->close();

  // Actualizar disponibilidad del espacio según nuevo estado
  if ($nuevo_estado === 'Aceptada') {
    $con->query("UPDATE espacio SET disponibilidad_espacio = 'Reservado' WHERE id_espacio = $id_espacio");
  } else if ($nuevo_estado === 'Rechazada') {
    $con->query("UPDATE espacio SET disponibilidad_espacio = 'Libre' WHERE id_espacio = $id_espacio");
  }

  echo json_encode(["ok" => true, "msg" => "Reserva $nuevo_estado correctamente."]);
  exit;
}

  throw new Exception("Acción no reconocida: $accion");

} catch (Throwable $e) {
  echo json_encode(["ok" => false, "msg" => $e->getMessage()]);
} finally {
  if ($con) $con->close();
}
