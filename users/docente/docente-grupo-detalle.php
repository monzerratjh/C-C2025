<?php
include('../../conexion.php');
session_start(); // sigue la sesion que ya tenia iniciada

ini_set('display_errors', 1); // activan la visualización de errores de PHP
error_reporting(E_ALL);

if (!isset($_SESSION['id_usuario'])) { //comprueba que es una sesion activa
  http_response_code(401); //muestra error 401 sino
  echo json_encode(["error" => "No autorizado"]);
  exit;
}

$con = conectar_bd();

$id_usuario = $_SESSION['id_usuario']; //toma el id del usuario logeado
$id_grupo = $_GET['id_grupo'] ?? null; //toma el id del grupo sino es null

if (!$id_grupo) { // si no encuentra id, muestra error
  echo json_encode(["error" => "Grupo no especificado"]);
  exit;
}


//prepara la consulta y asocia el ? con id 
$stmt = $con->prepare("SELECT id_docente FROM docente WHERE id_usuario = ?"); 
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$docente = $res->fetch_assoc(); //guarda los resultados en el array asociativo
$id_docente = $docente['id_docente'] ?? null; // comprueba que existe

if (!$id_docente) {
  echo json_encode(["error" => "Docente no encontrado"]);
  exit;
}


//consulta
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

//prepara la consulta
$stmt2 = $con->prepare($sql);
$stmt2->bind_param("ii", $id_docente, $id_grupo); //asocia parametros a ii
$stmt2->execute(); //ejecuta y guarda el resultado
$result = $stmt2->get_result();


//arma array con los datos
$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

//envia la respuesta en JSON
header('Content-Type: application/json'); 
echo json_encode($data);
?>
