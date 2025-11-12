                        //cuando el HTML ya se cargó 
document.addEventListener("DOMContentLoaded", () => {
  listarReservas();
});

// "async" indica que la función puede esperar tareas lentas (como una petición al servidor).
// Permite usar "await" sin bloquear la página.
async function listarReservas() {
  const tbody = document.querySelector("#tablaReservas tbody");
  tbody.innerHTML = "";
  document.querySelector("#sinReservas").textContent = "Cargando...";

  try {
    const fd = new FormData();
    fd.append("accion", "listar");
    const r = await fetch("./../reserva/adscripto-reservas-accion.php", { method: "POST", body: fd }); 
    const j = await r.json();

    if (!j.ok) throw new Error(j.msg);
    if (!j.data.length) {
      document.querySelector("#sinReservas").textContent = "No hay reservas pendientes.";
      return;
    }

    document.querySelector("#sinReservas").textContent = "";

    j.data.forEach(reserva => {
      const tr = document.createElement("tr");

      tr.innerHTML = `
        <td>${reserva.nombre_docente} ${reserva.apellido_docente}</td>
        <td>${reserva.nombre_grupo}</td>
        <td>${reserva.nombre_asignatura}</td>
        <td>${reserva.nombre_espacio}</td>
        <td>${reserva.dia}</td>
        <td>${reserva.fecha_reserva}</td>
        <td>${reserva.hora_inicio}</td>
        <td>${reserva.hora_fin}</td>
        <td><span class="badge bg-${colorEstado(reserva.estado_reserva)}">${reserva.estado_reserva}</span></td>
        <td>
          ${
            reserva.estado_reserva === "Pendiente"
              ? `
                <button class="btn btn-success btn-sm me-1" data-id="${reserva.id_reserva}" data-estado="Aceptada">
                  <i class="bi bi-check2-circle"></i> Aceptar
                </button>
                <button class="btn btn-danger btn-sm" data-id="${reserva.id_reserva}" data-estado="Rechazada">
                  <i class="bi bi-x-circle"></i> Rechazar
                </button>`
              : `<span class="text-muted small">-</span>`
          }
        </td>
      `;

      tbody.appendChild(tr);
    });

    // Asignar eventos a los botones dinámicos
    document.querySelectorAll("#tablaReservas button[data-id]").forEach(btn => {
      btn.addEventListener("click", async e => {
        const id_reserva = e.currentTarget.dataset.id;
        const nuevo_estado = e.currentTarget.dataset.estado;
        await cambiarEstado(id_reserva, nuevo_estado);
      });
    });

  } catch (err) {
    document.querySelector("#sinReservas").textContent = "Error al cargar reservas.";
    console.error(err);
  }
}

function colorEstado(estado) {
  switch (estado) {
    case "Pendiente": return "secondary";
    case "Aceptada": return "success";
    case "Rechazada": return "danger";
    case "Cancelada": return "warning";
    case "Finalizada": return "info";
    default: return "dark";
  }
}

async function cambiarEstado(id_reserva, nuevo_estado) {
  if (!confirm(`¿Seguro que deseas ${nuevo_estado.toLowerCase()} esta reserva?`)) return;

  const fd = new FormData();
  fd.append("accion", "cambiar_estado");
  fd.append("id_reserva", id_reserva);
  fd.append("nuevo_estado", nuevo_estado);

  try {
    const r = await fetch("./../reserva/adscripto-reservas-accion.php", { method: "POST", body: fd });
    const j = await r.json();
    if (!j.ok) throw new Error(j.msg);
    alert(j.msg);
    listarReservas();
  } catch (err) {
    alert("Error: " + err.message);
  }
}
