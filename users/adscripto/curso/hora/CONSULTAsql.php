$grupo = $_GET['grupo']; // "2° MD"
$sql = "
SELECT 
  g.nombre_grupo,
  a.nombre_asignatura,
  d.id_docente,
  e.nombre_espacio,
  hc.hora_inicio,
  hc.hora_fin,
  ha.dia
FROM grupo_asignatura_docente_aula gada
JOIN grupo g ON g.id_grupo = gada.id_grupo
JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
JOIN docente d ON d.id_docente = gada.id_docente
JOIN espacio e ON e.id_espacio = gada.id_espacio
JOIN horario_asignado ha ON ha.id_gada = gada.id_gada
JOIN horario_clase hc ON hc.id_horario_clase = ha.id_horario_clase
WHERE g.nombre_grupo = '$grupo'
ORDER BY FIELD(ha.dia, 'Lunes','Martes','Miércoles','Jueves','Viernes'), hc.hora_inicio;
";

  <script src="./../../../utils/desplegar-acordeon.js"></script>


  await pausa la ejecución de la función actual hasta que la promesa termine (ya sea con éxito o error).
Solo se puede usar dentro de una función async.