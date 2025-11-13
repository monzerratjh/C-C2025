<?php
include('../../../../conexion.php');
$con = conectar_bd();

// Si se pide el detalle por AJAX (POST con id_grupo)
if (isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
  $id_grupo = intval($_POST['id_grupo']);

  // Info del grupo
  $grupoInfo = mysqli_query($con, "SELECT nombre_grupo FROM grupo WHERE id_grupo = $id_grupo");
  $grupo = mysqli_fetch_assoc($grupoInfo);


  // Obtener horarios disponibles
$horarios = mysqli_query($con, "
  SELECT id_horario_clase, hora_inicio, hora_fin 
  FROM horario_clase 
  ORDER BY hora_inicio
");

// Obtener asignaturas y docentes asociados (desde GADA) para este grupo
$gada = mysqli_query($con, "
  SELECT 
    gada.id_gada,
    a.nombre_asignatura, 
    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) AS docente
  FROM grupo_asignatura_docente_aula gada
  JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
  JOIN docente d ON d.id_docente = gada.id_docente
  JOIN usuario u ON u.id_usuario = d.id_usuario
  WHERE gada.id_grupo = $id_grupo
  ORDER BY a.nombre_asignatura
");

// a.nombre_asignatura = asignatura.nomnbre_asignatura
//  JOIN usuario u ON u.id_usuario = d.id_usuario -> une las tablas docente y usuario para obtener el nombre completo del docente


// --

  // Horarios asignados
    $horarios_asignados = mysqli_query($con, "
    SELECT 
    ha.id_horario_asignado,
    hc.id_horario_clase,
    gada.id_gada,
    hc.hora_inicio,
    hc.hora_fin,
    a.nombre_asignatura,
    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) AS docente,
    e.nombre_espacio AS espacio,
    ha.dia

    FROM horario_asignado ha
    JOIN horario_clase hc ON hc.id_horario_clase = ha.id_horario_clase
    JOIN grupo_asignatura_docente_aula gada ON gada.id_gada = ha.id_gada
    JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
    JOIN docente d ON d.id_docente = gada.id_docente
    JOIN usuario u ON u.id_usuario = d.id_usuario
    LEFT JOIN espacio e ON gada.id_espacio = e.id_espacio
    WHERE gada.id_grupo = $id_grupo
    ORDER BY FIELD(ha.dia, 'Lunes','Martes','Miércoles','Jueves','Viernes'), hc.hora_inicio
    ");

  // ENUM días
  $enum_dias = [];
  $enum_query = mysqli_query($con, "SHOW COLUMNS FROM horario_asignado LIKE 'dia'");
  $row = mysqli_fetch_assoc($enum_query);
  preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
  $enum_dias = array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]));

  // Agrupar por día
  $horariosPorDia = [];
  while ($h = mysqli_fetch_assoc($horarios_asignados)) {
    $horariosPorDia[$h['dia']][] = $h;
  }

  ob_start(); /* inicia lo que se llama “buffer de salida” en PHP.

        Todo lo que se imprima (echo, HTML) se guarda en este buffer
        en lugar de enviarse inmediatamente al navegador. 
        
        Mas adelante con ob_get_clean() se obtiene el contenido del buffer como una cadena
        y se limpia el buffer. 
  */
  ?>
      <h2>Cargar horarios <?= htmlspecialchars($grupo['nombre_grupo']) ?></h2>

  <div class="text-center mb-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalHorario" data-id="<?= $id_grupo ?>">
      <h2>+</h2>
    </button>
  </div>

  <div class="acordion-total" id="acordeonDias">
        <div class="acordion">

    <?php foreach ($enum_dias as $index => $dia): ?>

      <div class="dia jueves">


      <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
            <?= $dia ?>
          </button>


        <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>">
          <div class="accordion-body">

            <?php if (!empty($horariosPorDia[$dia])): ?>

              <table class="tabla-reserva">
                  <tr>
                    <th>Horario entrada</th>
                    <th>Horario salida</th>
                    <th>Asignatura (Docente)</th>
                    <th>Espacio</th>
                    <th>Acciones</th>
                </tr>


                <tbody>
                  <?php foreach ($horariosPorDia[$dia] as $fila): ?>
                    <tr>
                    <td><?= substr($fila['hora_inicio'], 0, 5) ?></td>
                    <td><?= substr($fila['hora_fin'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_asignatura']) ?> (<?= htmlspecialchars($fila['docente']) ?>)</td>
                    <td><?= htmlspecialchars($fila['espacio'] ?? '-') ?></td>
                    <td>
                      <!-- BOTÓN EDITAR -->
                      <button type="button" class="btn btn-sm editar-btn"
                                data-id="<?= $fila['id_horario_asignado'] ?>"
                                data-dia="<?= htmlspecialchars($fila['dia']) ?>"
                                data-horario="<?= htmlspecialchars($fila['id_horario_clase']) ?>"
                                data-gada="<?= htmlspecialchars($fila['id_gada']) ?>"
                                data-bs-toggle="modal" data-bs-target="#modalHorario"
                                data-bs-target="#miModal">
                        <i class="bi bi-pencil-square"></i>
                     </button>


                      <!-- BOTÓN ELIMINAR -->
                      <form action="cargar-hora-accion.php" method="POST" style="display:inline;">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_grupo" value="<?= $id_grupo ?>">
                        <input type="hidden" name="id_horario_asignado" value="<?= $fila['id_horario_asignado'] ?>">
                        <button type="button" class="btn btn-sm btn-danger eliminar-btn"
                                data-id="<?= $fila['id_horario_asignado'] ?>"
                                data-grupo="<?= $id_grupo ?>">
                          <i class="bi bi-trash"></i>
                        </button>

                      </form>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p>Sin clases cargadas.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
  <?php
  echo ob_get_clean(); // obtiene y limpia el buffer de salida
  exit;
}

// Si se pide cargar opciones del modal
if (isset($_POST['accion']) && $_POST['accion'] === 'opciones') {
  $id_grupo = intval($_POST['id_grupo']);

  $horarios = mysqli_query($con, "SELECT id_horario_clase, hora_inicio, hora_fin FROM horario_clase ORDER BY hora_inicio");
  $gada = mysqli_query($con, "
    SELECT 
      gada.id_gada,
      a.nombre_asignatura,
      CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) AS docente
    FROM grupo_asignatura_docente_aula gada
    JOIN asignatura a ON a.id_asignatura = gada.id_asignatura
    JOIN docente d ON d.id_docente = gada.id_docente
    JOIN usuario u ON u.id_usuario = d.id_usuario
    WHERE gada.id_grupo = $id_grupo
    ORDER BY a.nombre_asignatura
  ");

  $enum_dias = [];
  $enum_query = mysqli_query($con, "SHOW COLUMNS FROM horario_asignado LIKE 'dia'");
  $row = mysqli_fetch_assoc($enum_query);
  preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
  $enum_dias = array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]));

  $data = [
    "dias" => $enum_dias,
    "horarios" => mysqli_fetch_all($horarios, MYSQLI_ASSOC),
    "gada" => mysqli_fetch_all($gada, MYSQLI_ASSOC)
  ];
  echo json_encode($data);
  exit;
}

// Grupos cargados por secretario 
$grupos = mysqli_query($con, "SELECT id_grupo, nombre_grupo FROM grupo ORDER BY nombre_grupo");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tercero MD horarios</title>
  
  <!-- Bootstrap CSS + Iconos + letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  
  <!-- CSS propio -->
  <link rel="stylesheet" href="./../../../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="./../../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="../hora/asignar-hora.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../../adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
    </div>
  </div>

  <!-- Contenedor principal con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../hora/asignar-hora.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="../hora/asignar-hora.php">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../../img/logo.png" alt="Logo" class="logo"> 

  <h2>Cargar horario</h2>
      <p>Ingrese el grupo en el cual va a agregar las horas dictadas.</p>

  <div class="input-group mb-3" style="max-width:500px;">
    <input type="text" list="lista-grupos" id="grupoInput" class="form-control" placeholder="Buscar grupo...">
    <datalist id="lista-grupos">
      <?php while ($g = mysqli_fetch_assoc($grupos)): ?>
        <option value="<?= htmlspecialchars($g['nombre_grupo']) ?>" data-id="<?= $g['id_grupo'] ?>"></option>
      <?php endwhile; ?>
    </datalist>
    <button class="btn btn-success" id="buscarGrupo">Buscar</button>
  </div>

  <div id="resultado"></div>

  <!-- Modal -->
<div class="modal fade" id="modalHorario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formHorario">
        <input type="hidden" name="id_grupo" id="modalGrupo">
        <input type="hidden" name="accion" id="accionForm" value="insertar">
        <input type="hidden" name="id_horario_asignado" id="idHorarioAsignado">

        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Agregar / Editar horario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Día</label>
            <select name="dia" id="diaSelect" class="form-select" required></select>
          </div>

          <div class="mb-3">
            <label class="form-label">Horario</label>
            <select name="id_horario_clase" id="horaSelect" class="form-select" required></select>
          </div>

          <div class="mb-3">
            <label class="form-label">Asignatura y docente</label>
            <select name="id_gada" id="gadaSelect" class="form-select" required></select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const input = document.getElementById('grupoInput');
const resultado = document.getElementById('resultado');
const modal = document.getElementById('modalHorario');

// Función para buscar grupo (reutilizable)
async function buscarGrupo() {
  const nombre = input.value.trim();
  const option = [...document.querySelectorAll('#lista-grupos option')]
                    .find(o => o.value === nombre);
  if (!option) return alert('Seleccione un grupo válido.');
  const id = option.dataset.id;

  const formData = new FormData();
  formData.append('accion', 'buscar');
  formData.append('id_grupo', id);

  const res = await fetch('cargar-horario.php', { method: 'POST', body: formData });
  const html = await res.text();
  resultado.innerHTML = html;
}


// Buscar al presionar Enter en el input
input.addEventListener('keydown', e => {
  if (e.key === 'Enter') {
    e.preventDefault(); // evita que se envíe un formulario si existe
    buscarGrupo();
  }
});

// Modal para agregar/editar horarios
modal.addEventListener('show.bs.modal', async e => {
const id = e.relatedTarget.dataset.id; // id_grupo o similar
  document.getElementById('modalGrupo').value = id;

  // --- 1. Obtener datos para llenar los selects ---
  const formData = new FormData();
  formData.append('accion', 'opciones');
  formData.append('id_grupo', id);

  const res = await fetch('cargar-horario.php', { method: 'POST', body: formData });
  const data = await res.json();

  // --- 2. Llenar los selects ---
  const diaSelect = document.getElementById('diaSelect');
  diaSelect.innerHTML = data.dias.map(d => `<option value="${d}">${d}</option>`).join('');

  const horaSelect = document.getElementById('horaSelect');
  horaSelect.innerHTML = data.horarios.map(h => 
    `<option value="${h.id_horario_clase}">${h.hora_inicio.substr(0,5)} - ${h.hora_fin.substr(0,5)}</option>`
  ).join('');

  const gadaSelect = document.getElementById('gadaSelect');
  gadaSelect.innerHTML = data.gada.map(g => 
    `<option value="${g.id_gada}">${g.nombre_asignatura} (${g.docente})</option>`
  ).join('');

  // --- 3. Si el botón que abrió el modal es "Editar" ---
  if (e.relatedTarget.classList.contains('editar-btn')) {

    document.getElementById('accionForm').value = 'editar';
    const btn = e.relatedTarget; // El botón de editar

    document.getElementById('idHorarioAsignado').value = btn.dataset.id;
    diaSelect.value = btn.dataset.dia;         // Día actual
    horaSelect.value = btn.dataset.horario;    // Horario actual
    gadaSelect.value = btn.dataset.gada;       // Asignatura + Docente actual (clave)

  } else {
    // --- 4. Si es "Agregar", reinicia el formulario ---
    document.getElementById('accionForm').value = 'insertar';
    document.getElementById('idHorarioAsignado').value = '';
    diaSelect.selectedIndex = 0;
    horaSelect.selectedIndex = 0;
    gadaSelect.selectedIndex = 0;
  }
});

// Guardar horario (Insertar o Editar)
document.getElementById('formHorario').addEventListener('submit', async e => {
  e.preventDefault();
  const data = new FormData(e.target);
  const res = await fetch('cargar-hora-accion.php', { method: 'POST', body: data });
  const json = await res.json();
  alert(json.message);
  if (json.type === 'success') {
    // Recargar lista de horarios
    const id = data.get('id_grupo');
    const fd = new FormData();
    fd.append('accion', 'buscar');
    fd.append('id_grupo', id);
    const res2 = await fetch('cargar-horario.php', { method: 'POST', body: fd });
    document.getElementById('resultado').innerHTML = await res2.text();
    bootstrap.Modal.getInstance(modal).hide();
  }
});

// Eliminar horario
document.addEventListener('click', async e => {
  if (e.target.closest('.eliminar-btn')) {
    const btn = e.target.closest('.eliminar-btn');
    const idHorario = btn.dataset.id;
    const idGrupo = btn.dataset.grupo;
    if (!confirm('¿Desea eliminar este horario?')) return;

    const fd = new FormData();
    fd.append('accion', 'eliminar');
    fd.append('id_horario_asignado', idHorario);
    const res = await fetch('cargar-hora-accion.php', { method: 'POST', body: fd });
    const json = await res.json();
    alert(json.message);
    if (json.type === 'success') {
      // Recargar tabla
      const fd2 = new FormData();
      fd2.append('accion', 'buscar');
      fd2.append('id_grupo', idGrupo);
      const res2 = await fetch('cargar-horario.php', { method: 'POST', body: fd2 });
      document.getElementById('resultado').innerHTML = await res2.text();
    }
  }
});
</script>
</body>
</html>