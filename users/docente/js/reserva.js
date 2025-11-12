// ============================
// UTILIDAD: obtener nombre del día
// ============================
function obtenerDiaSemana(fechaStr) {
  const dias = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
  const d = new Date(fechaStr + "T00:00:00");
  return dias[d.getDay()];
}

document.addEventListener("DOMContentLoaded", () => {
  const modalReserva = document.getElementById("modalReserva");
  if (modalReserva) {
    modalReserva.addEventListener("hidden.bs.modal", () => {
      location.reload();
    });
  }
  
  cargarGrupos();
  listarReservas();

  document.querySelector("#formReserva").addEventListener("submit", enviarReserva);

  // Observadores
  document.querySelector("#id_gada").addEventListener("change", validarDisponibilidadClases);
  document.querySelector("#fecha_reserva").addEventListener("change", validarDisponibilidadClases);
  document.querySelector("#cantidad_horas").addEventListener("input", generarCamposHoras);
});

// ============================
// CARGAR GRUPOS ASIGNADOS AL DOCENTE
// ============================
async function cargarGrupos() {
  const sel = document.querySelector("#id_gada");
  sel.innerHTML = "";
  try {
    const fd = new FormData();
    fd.append("accion", "cargar_grupos");
    const r = await fetch("./../reserva/reserva-accion.php", { method: "POST", body: fd });
    const j = await r.json();

    if (j.type !== "success") throw new Error(j.message || "Error cargando grupos.");

    if (!j.data.length) {
      sel.innerHTML = `<option>No tienes grupos asignados</option>`;
      sel.disabled = true;
      return;
    }

    sel.innerHTML = `<option value="">Seleccione</option>` +
      j.data.map(g => `<option value="${g.id_grupo}">${g.nombre_grupo} - ${g.nombre_asignatura} (${g.espacio_base})</option>`).join("");

  } catch (e) {
    sel.innerHTML = `<option>Error cargando grupos</option>`;
    Swal.fire("Error", e.message, "error");
  }
}

// ============================
// VALIDAR FECHA Y HABILITAR HORAS
// ============================
async function validarDisponibilidadClases() {
  const id_grupo = document.querySelector("#id_gada").value;
  const fecha = document.querySelector("#fecha_reserva").value;
  const inputHoras = document.querySelector("#cantidad_horas");
  const msg = document.querySelector("#msgHoras");

  inputHoras.value = "";
  inputHoras.disabled = true;
  msg.textContent = "Seleccione primero un grupo y una fecha donde tenga clase.";

  document.querySelector("#contenedorHorarios").innerHTML = "";
  document.querySelector("#id_espacio").innerHTML = `<option value="">Seleccione fecha y horarios primero</option>`;
  document.querySelector("#id_espacio").disabled = true;

  if (!id_grupo || !fecha) return;

  // Verifica formato YYYY-MM-DD
  const regexFecha = /^\d{4}-\d{2}-\d{2}$/;
  if (!regexFecha.test(fecha)) {
    msg.textContent = "Ingrese una fecha válida (DD-MM-AAAA).";
    return;
  }

  // No permitir fechas pasadas
  const hoy = new Date();
  const fechaSel = new Date(fecha + "T00:00:00");
  if (fechaSel < hoy.setHours(0,0,0,0)) {
    msg.textContent = "No puede reservar en fechas pasadas.";
    return;
  }

  const dia = obtenerDiaSemana(fecha);

  try {
    const res = await fetch(`./../reserva/reserva-accion.php?accion=cargar_horas&id_grupo=${id_grupo}&dia=${dia}`);
    const j = await res.json();

    if (j.type !== "success") throw new Error(j.message || "Error al verificar horarios.");

    if (!j.data.length) {
      msg.textContent = "No tienes clases asignadas ese día con este grupo.";
      return;
    }

    // habilitar cantidad_horas
    inputHoras.disabled = false;
    msg.textContent = "";

  } catch (e) {
    msg.textContent = "Error al verificar disponibilidad.";
    Swal.fire("Error", e.message, "error");
  }
}

// ============================
// GENERAR SELECTS DE HORARIOS
// ============================
function generarCamposHoras() {
  const cont = document.querySelector("#contenedorHorarios");
  cont.innerHTML = "";
  const n = parseInt(document.querySelector("#cantidad_horas").value || "0");
  if (!n) return;

  for (let i = 1; i <= n; i++) {
    const div = document.createElement("div");
    div.className = "mb-2";
    div.innerHTML = `
      <label class="form-label">Hora ${i}</label>
      <select class="form-select selHora" required>
        <option>Seleccione grupo y fecha primero</option>
      </select>`;
    cont.appendChild(div);
  }
  cargarHoras();
}

// ============================
// CARGAR HORARIOS DEL GRUPO
// ============================
async function cargarHoras() {
  const id_grupo = document.querySelector("#id_gada").value;
  const fecha = document.querySelector("#fecha_reserva").value;
  if (!id_grupo || !fecha) return;

  const dia = obtenerDiaSemana(fecha);
  try {
    const res = await fetch(`./../reserva/reserva-accion.php?accion=cargar_horas&id_grupo=${id_grupo}&dia=${dia}`);
    const j = await res.json();
    if (j.type !== "success") throw new Error(j.message || "Error al cargar horas.");

    const opts = `<option value="">Seleccione</option>` +
      j.data.map(h => `<option value="${h.id_horario_clase}"> ${h.hora_inicio} - ${h.hora_fin} </option>`).join("");

    document.querySelectorAll(".selHora").forEach(s => s.innerHTML = opts);
    document.querySelector("#id_espacio").disabled = false;
    cargarEspaciosLibres();
  } catch (e) {
    document.querySelectorAll(".selHora").forEach(s => s.innerHTML = `<option>Error cargando</option>`);
    Swal.fire("Error", e.message, "error");
  }
}

// ============================
// CARGAR ESPACIOS LIBRES
// ============================
async function cargarEspaciosLibres() {
  const sel = document.querySelector("#id_espacio");
  sel.innerHTML = `<option>Cargando espacios...</option>`;
  sel.disabled = true;

  try {
    const id_grupo = document.querySelector("#id_gada").value;
    if (!id_grupo) throw new Error("Seleccione un grupo primero.");

    // espacio asignado
    const resAsignado = await fetch(`./../reserva/reserva-accion.php?accion=espacio_asignado&id_grupo=${id_grupo}`);
    const dataAsignado = await resAsignado.json();
    const id_espacio_asignado = dataAsignado?.id_espacio ?? null;

    // espacios libres
    const fd = new FormData();
    fd.append("accion", "cargar_espacios");
    const r = await fetch("./../reserva/reserva-accion.php", { method: "POST", body: fd });
    const j = await r.json();

    if (j.type !== "success") throw new Error(j.message || "Error al cargar espacios.");

    const espacios = j.data.filter(e => e.id_espacio != id_espacio_asignado);
    if (!espacios.length) {
      sel.innerHTML = `<option>No hay espacios libres</option>`;
      return;
    }

    sel.innerHTML = `<option value="">Seleccione un espacio</option>` +
      espacios.map(e =>
        `<option value="${e.id_espacio}">${e.nombre_espacio} (${e.tipo_espacio} - cap. ${e.capacidad_espacio})</option>`
      ).join("");

    sel.disabled = false;
  } catch (e) {
    sel.innerHTML = `<option>Error cargando espacios</option>`;
    Swal.fire("Error", e.message, "error");
  }
}

// ============================
// ENVIAR RESERVA
// ============================
async function enviarReserva(ev) {
  ev.preventDefault();

  const id_grupo = document.querySelector("#id_gada").value;
  const fecha_reserva = document.querySelector("#fecha_reserva").value;
  const dia = fecha_reserva ? obtenerDiaSemana(fecha_reserva) : "";
  const cantidad_horas = parseInt(document.querySelector("#cantidad_horas").value || "0");
  const id_espacio = document.querySelector("#id_espacio").value;
  const observacion = document.querySelector("#observacion")?.value.trim() ?? "";
  const ids_horario = [...document.querySelectorAll(".selHora")].map(s => s.value).filter(Boolean);

  // Validaciones
  if (!id_grupo) return Swal.fire("Faltan datos", "Debe seleccionar un grupo.", "warning");
  if (!fecha_reserva) return Swal.fire("Faltan datos", "Debe seleccionar una fecha.", "warning");
  if (!cantidad_horas || cantidad_horas < 1) return Swal.fire("Faltan datos", "Indique cuántas horas reservar.", "warning");
  if (ids_horario.length !== cantidad_horas) return Swal.fire("Validación", "Debe seleccionar exactamente las horas indicadas.", "info");
  if (!id_espacio) return Swal.fire("Faltan datos", "Seleccione un espacio.", "warning");

  //validar duplicados
  const duplicados = ids_horario.filter((v, i, arr) => arr.indexOf(v) !== i);
  if (duplicados.length > 0) {
    document.querySelectorAll(".selHora").forEach(sel => {
      if (duplicados.includes(sel.value)) {
        sel.classList.add("is-invalid");
        setTimeout(() => sel.classList.remove("is-invalid"), 2000);
      }
    });
    return Swal.fire("Horas duplicadas", "Has seleccionado la misma hora más de una vez.", "warning");
  }

  // Confirmación
  const { isConfirmed } = await Swal.fire({
    title: "Confirmar solicitud",
    html: `
      <div class="text-start">
        <p><b>Fecha:</b> ${fecha_reserva} (${dia})</p>
        <p><b>Horas:</b> ${ids_horario.length}</p>
        <p><b>Espacio:</b> ${document.querySelector("#id_espacio").selectedOptions[0]?.textContent ?? id_espacio}</p>
      </div>
    `,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Enviar",
    cancelButtonText: "Cancelar"
  });
  if (!isConfirmed) return;

  // Enviar al backend
  const fd = new FormData();
  fd.append("accion", "crear");
  fd.append("id_grupo", id_grupo);
  fd.append("fecha_reserva", fecha_reserva);
  fd.append("dia", dia);
  fd.append("cantidad_horas", cantidad_horas);
  fd.append("id_espacio", id_espacio);
  fd.append("observacion", observacion);
  ids_horario.forEach(h => fd.append("ids_horario[]", h));

  try {
    const r = await fetch("./../reserva/reserva-accion.php", { method: "POST", body: fd });
    const j = await r.json();

    if (j.type === "success") {
      await Swal.fire("Éxito", j.message, "success");
      location.reload(); // recarga la lista SOLO si todo salió bien
      listarReservas();
      document.querySelector("#formReserva").reset();
      document.querySelector("#contenedorHorarios").innerHTML = "";
    } else {
      Swal.fire("Error", j.message, "error");
    }
  } catch (e) {
    Swal.fire("Error", e.message, "error");
  }
}

// ============================
// LISTAR RESERVAS
// ============================
async function listarReservas() {
  const tbody = document.querySelector("#tablaReservas tbody");
  const listaMovil = document.querySelector("#reservasMovil");
  const sinDesktop = document.querySelector("#sinReservasDesktop");
  const sinMovil = document.querySelector("#sinReservasMovil");

  tbody.innerHTML = "";
  listaMovil.innerHTML = "";
  sinDesktop.textContent = "Cargando...";
  sinMovil.textContent = "Cargando...";

  try {
    const fd = new FormData();
    fd.append("accion", "listar");
    const r = await fetch("./../reserva/reserva-accion.php", { method: "POST", body: fd });
    const j = await r.json();

    if (j.type !== "success") throw new Error(j.message || "Error al listar reservas.");

    if (!j.data.length) {
      sinDesktop.textContent = "No tienes reservas aún.";
      sinMovil.textContent = "No tienes reservas aún.";
      return;
    }

    sinDesktop.textContent = "";
    sinMovil.textContent = "";

    j.data.forEach(item => {
      // ====== MODO DESKTOP ======
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${item.nombre_grupo}</td>
        <td>${item.nombre_asignatura}</td>
        <td>${item.nombre_espacio}</td>
        <td>${item.dia}</td>
        <td>${item.fecha_reserva}</td>
        <td>${item.hora_inicio}</td>
        <td>${item.hora_fin}</td>
        <td><span class="badge bg-${colorEstado(item.estado_reserva)}">${item.estado_reserva}</span></td>
        <td>
          ${["Pendiente","Aceptada"].includes(item.estado_reserva)
            ? `<button class="btn btn-sm  " data-cancel="1" data-hc="${item.id_horario_clase}" data-esp="${item.id_espacio}">
                 <i class="bi bi-x-circle"></i> Cancelar
               </button>`
            : `<span class="text-muted small">-</span>`}
        </td>`;
      tbody.appendChild(tr);

      // ====== MODO RESPONSIVE ======
      const trMovil = document.createElement("tr");
      trMovil.innerHTML = `
        <td><b>Grupo:</b> ${item.nombre_grupo}</td>
        <td><b>Asignatura:</b> ${item.nombre_asignatura}</td>
        <td><b>Espacio:</b> ${item.nombre_espacio}</td>
        <td><b>Día:</b> ${item.dia}</td>
        <td><b>Fecha:</b> ${item.fecha_reserva}</td>
        <td><b>Inicio:</b> ${item.hora_inicio}</td>
        <td><b>Fin:</b> ${item.hora_fin}</td>
        <td><b>Estado:</b> <span class="badge bg-${colorEstado(item.estado_reserva)}">${item.estado_reserva}</span></td>
        <td>
          ${
            ["Pendiente","Aceptada"].includes(item.estado_reserva)
              ? `<button class="btn btn-sm   w-100 mt-2" data-cancel="1" data-hc="${item.id_horario_clase}" data-esp="${item.id_espacio}">
                   <i class="bi bi-x-circle"></i> Cancelar
                 </button>`
              : `<span class="text-muted small">-</span>`
          }
        </td>`;
      listaMovil.appendChild(trMovil);
    });

    // Acciones: cancelar reserva
    document.querySelectorAll("[data-cancel='1']").forEach(btn => {
      btn.addEventListener("click", async (e) => {
        const id_horario_clase = e.currentTarget.dataset.hc;
        const id_espacio = e.currentTarget.dataset.esp;

        const { isConfirmed } = await Swal.fire({
          title: "Cancelar reserva",
          text: "¿Seguro que deseas cancelar esta reserva?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, cancelar",
          cancelButtonText: "No"
        });
        if (!isConfirmed) return;

        try {
          const fd = new FormData();
          fd.append("accion", "cancelar");
          fd.append("id_horario_clase", id_horario_clase);
          fd.append("id_espacio", id_espacio);
          const r = await fetch("./../reserva/reserva-accion.php", { method: "POST", body: fd });
          const j = await r.json();
          if (j.type !== "success") throw new Error(j.message || "No se pudo cancelar.");

          await Swal.fire("Hecho", j.message || "Reserva cancelada.", "success");
          listarReservas();
        } catch (err) {
          Swal.fire("Error", err.message, "error");
        }
      });
    });

  } catch (e) {
    sinDesktop.textContent = "Error al cargar reservas.";
    sinMovil.textContent = "Error al cargar reservas.";
    Swal.fire("Error", e.message, "error");
  }
}

// ============================
// COLORES DE ESTADOS
// ============================
function colorEstado(e) {
  switch (e) {
    case "Pendiente": return "warning";     // amarillo: esperando
    case "Aceptada": return "success";      // verde: aceptada
    case "Rechazada": return "danger";      // rojo: rechazada
    case "Cancelada": return "secondary";   // gris: cancelada
    case "Finalizada": return "info";       // celeste: finalizada
    default: return "dark";                 // fallback
  }
}

// ============================
// EVITAR REENVÍO AL RECARGAR
// ============================
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.pathname);
}