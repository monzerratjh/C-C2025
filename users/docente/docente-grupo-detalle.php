<?php
include('../../conexion.php');
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id_usuario'])) {
  http_response_code(401);
  echo json_encode(["error" => "No autorizado"]);
  exit;
}

$con = conectar_bd();

$id_usuario = $_SESSION['id_usuario'];
$id_grupo = $_GET['id_grupo'] ?? null;

if (!$id_grupo) {
  echo json_encode(["error" => "Grupo no especificado"]);
  exit;
}

$stmt = $con->prepare("SELECT id_docente FROM docente WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$docente = $res->fetch_assoc();
$id_docente = $docente['id_docente'] ?? null;

if (!$id_docente) {
  echo json_encode(["error" => "Docente no encontrado"]);
  exit;
}

$sql = "
  SELECT 
    ha.dia,
    hc.hora_inicio,
    hc.hora_fin,
    e.nombre_espacio,
    a.nombre_asignatura
  FROM horario_asignado ha
  JOIN horario_clase hc ON hc.id_horario_clase = ha.id_horario_clase
  JOIN grupo_asignatura_docente_aula gada ON gada.id_gada = ha.id_gada
  JOIN espacio e ON e.id_espacio = gada.id_espacio
  JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
  WHERE gada.id_docente = ? 
    AND gada.id_grupo = ?
  ORDER BY FIELD(ha.dia, 'Lunes','Martes','Miércoles','Jueves','Viernes'), hc.hora_inicio
";
$stmt2 = $con->prepare($sql);
$stmt2->bind_param("ii", $id_docente, $id_grupo);
$stmt2->execute();
$result = $stmt2->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
