document.addEventListener("DOMContentLoaded", () => { //espera a que el html este cargado para ejecutar lo que sigue
  const botones = document.querySelectorAll(".boton-opciones"); //selecciona boton-opciones

  botones.forEach(btn => { //recorre todos los botones
    btn.addEventListener("click", async () => { //le agrega a cada boton un click 
      const grupoId = btn.dataset.id; //obtiene el valor del atributo data-id
      const content = btn.nextElementSibling; // div.contenido-grupo (el boton siguiente del html)
      const tbody = content.querySelector(".tabla-docente tbody");  //busca estas tablas
      const tbodyResponsive = content.querySelector(".tabla-docente-responsive tbody"); // y donde se va a insertar las filas

      // --- Cerrar otros grupos cuando se abre uno diferente ---
      document.querySelectorAll(".contenido-grupo").forEach(div => { //selecciona todos los contenido-grupo
        if (div !== content) { //si alguno no es el que acaba de abrir
          div.classList.remove("activo"); // le saca lo activo
          const otherBtn = div.previousElementSibling; // le saca lo activo
          if (otherBtn) otherBtn.classList.remove("active");// le saca lo activo si otro lo tiene
        }
        //Devuelve el elemento hermano anterior del elemento actual. PREVIUOSELEMENTSIBLING
      });

      // --- Alternar visibilidad ---
      const visible = content.classList.contains("activo");
      content.classList.toggle("activo", !visible);
      btn.classList.toggle("active", !visible);

      if (visible) return; // si se cierra, no recarga datos

      //  Mensaje temporal de carga 
      //Limpia el contenido anterior antes de poner el nuevo.
      tbody.innerHTML = "";
      tbodyResponsive.innerHTML = "";

      try {
        // hace una petición HTTP al archivo PHP, pasándole el id_grupo
        const resp = await fetch(`docente-grupo-detalle.php?id_grupo=${grupoId}`);
        
        // pasa el id grupo y se espera una respuesta
        if (!resp.ok) throw new Error("Error al conectar con el servidor");
        const data = await resp.json(); // pasa la respuesta a un array js

        // --- Rellenar tabla principal ---
        data.forEach(item => { //recorre y crea una fila para cada clase
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${item.dia}</td>
            <td>${item.nombre_asignatura}</td>
            <td>${item.hora_inicio}</td>
            <td>${item.hora_fin}</td>
            <td>${item.nombre_espacio}</td>
          `;
          tbody.appendChild(tr); //inserta esa fila dentro del <tbody>
        });

        // Rellenar tabla responsive  (lo mismo que lo anterior pero en la responsive)
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

        //cargar errores
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
