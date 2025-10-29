<?php 
include('./../../../../conexion.php');
$con = conectar_bd();

// Si se pide el detalle por AJAX (POST con id_grupo)
if (isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
  $id_grupo = intval($_POST['id_grupo']);

  // Info del grupo
  $grupoInfo = mysqli_query($con, "SELECT nombre_grupo FROM grupo WHERE id_grupo = $id_grupo");
  $grupo = mysqli_fetch_assoc($grupoInfo);

  // Horarios asignados
  $horarios_asignados = mysqli_query($con, "
    SELECT 
      ha.id_horario_asignado,
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
    ORDER BY FIELD(ha.dia,'Lunes','Martes','Miércoles','Jueves','Viernes'), hc.hora_inicio
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

      <!-- Botón para agregar horario -->
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalHorario"
              data-id="<?= $id_grupo ?>">
        <h2>+</h2>
      </button>

      <div class="acordion-total">
        <div class="acordion">

        <?php foreach ($enum_dias as $dia): 
            $lista = $horariosPorDia[$dia] ?? [];
        ?>

          <div class="dia">
           <button class=" toggle-dia boton-opciones  colorletrablanco jueves" ><?= $dia ?></button>
            <div class="contenido-dia">
              <?php if ($lista): ?>
                <table class="tabla-reserva">
                  <tr>
                    <th>Horario entrada</th>
                    <th>Horario salida</th>
                    <th>Asignatura (Docente)</th>
                    <th>Espacio</th>
                    <th>Acciones</th>
                </tr>
              
                <tbody>
                <?php foreach ($lista as $fila): ?>
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
                              data-bs-toggle="modal" data-bs-target="#modalHorario">
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
              <p>Sin clases cargadas</p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
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
  <title>Panel Adscriptos</title>
  
  <!-- Bootstrap CSS + Iconos + letras-->
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
        <a href="../adscripto-curso.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="../../curso/adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
    </div>
  </div>

  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral d-none d-md-flex">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../adscripto-curso.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="../adscripto-curso.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../../curso/adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../../../../img/logo.png" alt="Logo" class="logo"> 
      
      <h2>Cargar Hora</h2>
      <p>Ingrese el grupo en el cual va a agregar las horas dictadas.</p>

      <div class="busqueda">
        <i class="bi bi-search icono-busqueda"></i>
        <input 
          type="text" 
          class="diseno-busqueda diseno-busqueda2" 
          placeholder="Ingrese el grupo" 
          list="lista-grupos" 
          id="grupoInput"
        />
        <datalist id="lista-grupos">
          <?php while ($g = mysqli_fetch_assoc($grupos)): ?>
            <option value="<?= htmlspecialchars($g['nombre_grupo']) ?>" 
                    data-id="<?= $g['id_grupo'] ?>"></option>

            </option>
          <?php endwhile; ?>
        </datalist> 
      </div>

      <div id="resultado"></div>

      <!-- Modal -->
    <div class="modal fade" id="modalHorario" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="cargar-hora-accion.php">

          <input type="hidden" name="id_grupo" value="<?= $id_grupo ?>">

            <div class="modal-header bg-success text-white">
              <h5 class="modal-title">Agregar / Editar horario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <input type="hidden" name="accion" id="accionForm" value="insertar">
              <input type="hidden" name="id_horario_asignado" id="idHorarioAsignado">

              <div class="mb-3">
                <label class="form-label">Día</label>
                <select name="dia" id="dia" class="form-select" required>
                  <option value="">Seleccione...</option>
                  <?php foreach ($enum_dias as $d): ?>
                    <option value="<?= $d ?>"><?= $d ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Horario</label>
                <select name="id_horario_clase" id="id_horario_clase" class="form-select" required>
                  <option value="">Seleccione...</option>
                  <?php
                  mysqli_data_seek($horarios, 0);
                  while ($h = mysqli_fetch_assoc($horarios)): ?>
                    <option value="<?= $h['id_horario_clase'] ?>">
                      <?= substr($h['hora_inicio'], 0, 5) ?> - <?= substr($h['hora_fin'], 0, 5) ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Asignatura y docente</label>
                <select name="id_gada" id="id_gada" class="form-select" required>
                  <option value="">Seleccione...</option>
                  <?php
                  mysqli_data_seek($gada, 0);
                  while ($g = mysqli_fetch_assoc($gada)): ?>
                    <option value="<?= $g['id_gada'] ?>">
                      <?= $g['nombre_asignatura'] ?> (<?= $g['docente'] ?>)
                    </option>
                  <?php endwhile; ?>
                </select>
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

    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./../../js/hora.js"></script>

  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../../utils/translate.js"></script>

<script>
    // ==============================
// VARIABLES
// ==============================
const input = document.getElementById('grupoInput');
const resultado = document.getElementById('resultado');
const modalEl = document.getElementById('modalHorario');
const modal = new bootstrap.Modal(modalEl);

// ==============================
// FUNCIONES
// ==============================
async function buscarGrupo() {

  document.querySelectorAll('.toggle-dia').forEach(btn => {
  btn.addEventListener('click', () => {
    const cont = btn.nextElementSibling;
    cont.style.display = cont.style.display === 'block' ? 'none' : 'block';
  });
});


  const nombre = input.value.trim();
  //permitir coincidencia parcial o ignorar acentos:
function normalizar(texto) {
  return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

const option = [...document.querySelectorAll('#lista-grupos option')]
              .find(o => normalizar(o.value).includes(normalizar(nombre)));



if (!option || !option.dataset.id) {
  return Swal.fire({
    icon: 'error',
    title: 'Grupo inválido',
    text: 'Seleccione un grupo válido de la lista.',
    confirmButtonColor: '#198754'
  });
}


  const id = option.dataset.id;
  const formData = new FormData();
  formData.append('accion', 'buscar');
  formData.append('id_grupo', id);

  try {

    console.log(nombre, option)

    const res = await fetch('cargar-hora.php', { method: 'POST', body: formData });
    const html = await res.text();
    resultado.innerHTML = html;

    // Reasignar eventos a botones de editar/eliminar de los nuevos horarios
    asignarEventosHorarios();
  } catch (err) {
    console.error(err);
    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el horario.' });
  }
}

// ==============================
// BÚSQUEDA CON ENTER
// ==============================
input.addEventListener('keydown', e => {
  if (e.key === 'Enter') {
    e.preventDefault();
    buscarGrupo();
  }
});

// ==============================
// TOGGLE DÍAS
// ==============================
document.querySelectorAll('.toggle-dia').forEach(btn => {
  btn.addEventListener('click', () => {
    const cont = btn.nextElementSibling;
    cont.style.display = cont.style.display === 'block' ? 'none' : 'block';
  });
});

// ==============================
// MODAL AGREGAR / EDITAR HORARIO
// ==============================
modalEl.addEventListener('show.bs.modal', async e => {
  const id = e.relatedTarget.dataset.id;

  // Asignar dinámicamente el ID del grupo al input hidden del modal
  document.querySelector("#modalHorario input[name='id_grupo']").value = id;

  const formData = new FormData();
  formData.append('accion', 'opciones');
  formData.append('id_grupo', id);

  try {
    const res = await fetch('cargar-hora.php', { method: 'POST', body: formData });
    const data = await res.json();

    document.getElementById('dia').innerHTML = data.dias.map(d => `<option>${d}</option>`).join('');
    document.getElementById('id_horario_clase').innerHTML = data.horarios.map(h => 
      `<option value="${h.id_horario_clase}">${h.hora_inicio.substr(0,5)} - ${h.hora_fin.substr(0,5)}</option>`
    ).join('');
    document.getElementById('id_gada').innerHTML = data.gada.map(g => 
      `<option value="${g.id_gada}">${g.nombre_asignatura} (${g.docente})</option>`
    ).join('');

  } catch (err) {
    console.error(err);
    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar las opciones del modal.' });
  }
});

// ==============================
// VALIDACIÓN Y ENVÍO FORMULARIO
// ==============================
document.querySelector("#modalHorario form").addEventListener("submit", async (event) => {
  event.preventDefault();
  const formHorario = event.target;

  const formData = new FormData(formHorario);
  const dia = formData.get("dia").trim();
  const horario = formData.get("id_horario_clase").trim();
  const gada = formData.get("id_gada").trim();

  if (!dia || !horario || !gada) {
    return Swal.fire({
      icon: "error",
      title: "Campos incompletos",
      text: "Complete todos los campos antes de guardar.",
      confirmButtonColor: "#198754",
    });
  }

  const result = await Swal.fire({
    title: "¿Desea guardar los cambios?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, guardar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#198754",
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("cargar-hora-accion.php", { method: "POST", body: formData });
      const data = await res.json();

      Swal.fire({
        icon: data.type,
        title: data.type === "success" ? "Éxito" : "Error",
        text: data.message,
        confirmButtonColor: "#198754",
      }).then(() => {
        if (data.type === "success") {
          buscarGrupo(); // recarga la lista sin refrescar la página
          modal.hide();
        }
      });
    } catch (err) {
      console.error(err);
      Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo guardar el horario.' });
    }
  }
});

// ==============================
// ELIMINAR HORARIO
// ==============================
function asignarEventosHorarios() {
  document.querySelectorAll(".eliminar-btn").forEach(btn => {
    btn.addEventListener("click", async () => {
      const id = btn.dataset.id;
      const grupo = btn.dataset.grupo;

      const result = await Swal.fire({
        title: "¿Eliminar este horario?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#dc3545",
      });

      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("accion","eliminar");
        formData.append("id_horario_asignado", id);
        formData.append("id_grupo", grupo);

        try {
          const res = await fetch("cargar-hora-accion.php",{method:"POST",body:formData});
          const data = await res.json();
          Swal.fire({icon:data.type, title:data.type==='success'?'Eliminado':'Error', text:data.message})
            .then(()=> { if(data.type==='success') buscarGrupo(); });
        } catch (err) {
          console.error(err);
          Swal.fire({icon:'error', title:'Error', text:'No se pudo eliminar el horario.'});
        }
      }
    });
  });
}

// ==============================
// INICIALIZAR EVENTOS AL CARGAR
// ==============================
document.addEventListener("DOMContentLoaded", () => {
  asignarEventosHorarios();
});
</script>

</body>
</html>