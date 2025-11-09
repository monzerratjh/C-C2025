document.addEventListener("DOMContentLoaded", () => {
  const botones = document.querySelectorAll(".boton-opciones");

  botones.forEach(btn => {
    btn.addEventListener("click", async () => {
      const grupoId = btn.dataset.id;
      const content = btn.nextElementSibling; // div.contenido-grupo
      const tbody = content.querySelector(".tabla-docente tbody");
      const tbodyResponsive = content.querySelector(".tabla-docente-responsive tbody");

      // --- Cerrar otros grupos ---
      document.querySelectorAll(".contenido-grupo").forEach(div => {
        if (div !== content) {
          div.classList.remove("activo");
          const otherBtn = div.previousElementSibling;
          if (otherBtn) otherBtn.classList.remove("active");
        }
      });

      // --- Alternar visibilidad ---
      const visible = content.classList.contains("activo");
      content.classList.toggle("activo", !visible);
      btn.classList.toggle("active", !visible);

      if (visible) return; // si se cierra, no recarga datos

      // --- Mensaje temporal de carga ---
      tbody.innerHTML = "";
      tbodyResponsive.innerHTML = "";

      try {
        // --- Petición al servidor ---
        const resp = await fetch(`docente-grupo-detalle.php?id_grupo=${grupoId}`);
        if (!resp.ok) throw new Error("Error al conectar con el servidor");
        const data = await resp.json();

        // --- Rellenar tabla principal ---
        data.forEach(item => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${item.dia}</td>
            <td>${item.nombre_asignatura}</td>
            <td>${item.hora_inicio}</td>
            <td>${item.hora_fin}</td>
            <td>${item.nombre_espacio}</td>
          `;
          tbody.appendChild(tr);
        });

        // --- Rellenar tabla responsive ---
        data.forEach(item => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td><b>Día:</b> ${item.dia}</td>
            <td><b>Asignatura:</b> ${item.nombre_asignatura}</td>
            <td><b>Inicio:</b> ${item.hora_inicio}</td>
            <td><b>Fin:</b> ${item.hora_fin}</td>
            <td><b>Espacio:</b> ${item.nombre_espacio}</td>
          `;
          tbodyResponsive.appendChild(tr);
        });

      } catch (error) {
        console.error("Error al cargar los datos:", error);
        tbody.innerHTML = `
          <tr><td colspan="5" class="text-danger text-center">Error al cargar los datos.</td></tr>
        `;
        tbodyResponsive.innerHTML = `
          <tr><td class="text-danger text-center">Error al cargar los datos.</td></tr>
        `;
      }
    });
  });
});
