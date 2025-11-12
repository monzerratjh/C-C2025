// ============================
// UTILIDADES
// ============================
function colorEstado(e) {
  switch (e) {
    case "Pendiente": return "warning";
    case "Aceptada": return "success";
    case "Rechazada": return "danger";
    case "Cancelada": return "secondary";
    case "Finalizada": return "info";
    default: return "dark";
  }
}

// ============================
// LISTAR RESERVAS (desktop + responsive)
// ============================
document.addEventListener("DOMContentLoaded", () => {
  listarReservas();
  setupResponsiveToggle();
});

async function listarReservas() {
  const tbody = document.querySelector("#tablaReservas tbody");
  const tbodyResponsive = document.querySelector(".tabla-adscripto-responsive tbody");
  const sinReservas = document.getElementById("sinReservas");
  const sinReservasMovil = document.getElementById("sinReservasMovil");

  // Limpiar contenido previo
  tbody.innerHTML = "";
  tbodyResponsive.innerHTML = "";
  sinReservas.style.display = "block";
  sinReservasMovil.style.display = "block";
  sinReservas.textContent = "Cargando reservas...";
  sinReservasMovil.textContent = "Cargando reservas...";

  try {
    const fd = new FormData();
    fd.append("accion", "listar");
    const r = await fetch("./../reserva/adscripto-reservas-accion.php", { method: "POST", body: fd });
    const j = await r.json();

    if (!j.ok) throw new Error(j.msg);

    if (!j.data.length) {
      sinReservas.textContent = "No hay reservas pendientes.";
      sinReservasMovil.textContent = "No hay reservas pendientes.";
      return;
    }

    // Ocultar mensajes cuando hay datos
    sinReservas.style.display = "none";
    sinReservasMovil.style.display = "none";

    j.data.forEach(item => {
      // ====== DESKTOP ======
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${item.nombre_docente} ${item.apellido_docente}</td>
        <td>${item.nombre_grupo}</td>
        <td>${item.nombre_asignatura}</td>
        <td>${item.nombre_espacio}</td>
        <td>${item.dia}</td>
        <td>${item.fecha_reserva}</td>
        <td>${item.hora_inicio}</td>
        <td>${item.hora_fin}</td>
        <td><span class="badge bg-${colorEstado(item.estado_reserva)}">${item.estado_reserva}</span></td>
        <td>
          ${
            item.estado_reserva === "Pendiente"
              ? `<button class="btn btn-success btn-sm me-1" data-id="${item.id_reserva}" data-estado="Aceptada">Aceptar</button>
                 <button class="btn btn-danger btn-sm" data-id="${item.id_reserva}" data-estado="Rechazada">Rechazar</button>`
              : `<span class="text-muted small">-</span>`
          }
        </td>
      `;
      tbody.appendChild(tr);

      // ====== RESPONSIVE ======
      const trResponsive = document.createElement("tr");
      trResponsive.innerHTML = `
        <td><b>Docente:</b> ${item.nombre_docente} ${item.apellido_docente}</td>
        <td><b>Grupo:</b> ${item.nombre_grupo}</td>
        <td><b>Asignatura:</b> ${item.nombre_asignatura}</td>
        <td><b>Espacio:</b> ${item.nombre_espacio}</td>
        <td><b>Día:</b> ${item.dia}</td>
        <td><b>Fecha:</b> ${item.fecha_reserva}</td>
        <td><b>Hora inicio:</b> ${item.hora_inicio}</td>
        <td><b>Hora fin:</b> ${item.hora_fin}</td>
        <td><span class="badge bg-${colorEstado(item.estado_reserva)}">${item.estado_reserva}</span></td>
        <td>
          ${
            item.estado_reserva === "Pendiente"
              ? `<button class="btn btn-success btn-sm me-1 w-100 mb-1" data-id="${item.id_reserva}" data-estado="Aceptada">Aceptar</button>
                 <button class="btn btn-danger btn-sm w-100" data-id="${item.id_reserva}" data-estado="Rechazada">Rechazar</button>`
              : `<span class="text-muted small">-</span>`
          }
        </td>
      `;
      tbodyResponsive.appendChild(trResponsive);
    });

    // Asignar eventos a botones dinámicos (desktop + responsive)
    document.querySelectorAll("[data-id]").forEach(btn => {
      btn.addEventListener("click", async e => {
        const id_reserva = e.currentTarget.dataset.id;
        const nuevo_estado = e.currentTarget.dataset.estado;
        await cambiarEstado(id_reserva, nuevo_estado);
      });
    });

  } catch (err) {
    sinReservas.style.display = "block";
    sinReservasMovil.style.display = "block";
    sinReservas.textContent = "Error al cargar reservas.";
    sinReservasMovil.textContent = "Error al cargar reservas.";
    console.error(err);
  }
}


// ============================
// CAMBIAR ESTADO DE RESERVA
// ============================
async function cambiarEstado(id_reserva, nuevo_estado) {
  const confirm = await Swal.fire({
    title: `${nuevo_estado} reserva`,
    text: `¿Seguro que deseas ${nuevo_estado.toLowerCase()} esta reserva?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí",
    cancelButtonText: "No"
  });
  if (!confirm.isConfirmed) return;

  try {
    const fd = new FormData();
    fd.append("accion", "cambiar_estado");
    fd.append("id_reserva", id_reserva);
    fd.append("nuevo_estado", nuevo_estado);

    const r = await fetch("./../reserva/adscripto-reservas-accion.php", { method: "POST", body: fd });
    const j = await r.json();
    if (!j.ok) throw new Error(j.msg);

    await Swal.fire("Éxito", j.msg, "success");
    listarReservas();
  } catch (err) {
    Swal.fire("Error", err.message, "error");
  }
}

// ============================
// TOGGLE RESPONSIVE
// ============================
function setupResponsiveToggle() {
  const verBtn = document.getElementById("verReservasAdscriptoBtn");
  const tablaResp = document.querySelector(".tabla-adscripto-responsive");

  if (verBtn && tablaResp) {
    tablaResp.style.display = "none"; // cerrado al cargar

    verBtn.addEventListener("click", () => {
      const isVisible = tablaResp.style.display === "block";

      if (!isVisible) {
        tablaResp.style.display = "block";
        verBtn.classList.add("active");
      } else {
        tablaResp.style.display = "none";
        verBtn.classList.remove("active");
      }
    });
  }
}

// ============================
// EVITAR REENVÍO AL RECARGAR
// ============================
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.pathname);
}
