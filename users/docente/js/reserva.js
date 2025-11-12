
// UTILIDAD: obtener nombre del día
function obtenerDiaSemana(fechaStr) {
  const dias = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
  const d = new Date(fechaStr + "T00:00:00"); //// Se crea un objeto Date a partir del string recibido (fechaStr)
  return dias[d.getDay()]; //// El método getDay() devuelve el número del día de la semana (0 = Domingo, 6 = Sábado),
}

document.addEventListener("DOMContentLoaded", () => {

  const modalReserva = document.getElementById("modalReserva");
  if (modalReserva) {

     // Cuando el modal se oculta (evento Bootstrap "hidden.bs.modal"), recarga la página
    modalReserva.addEventListener("hidden.bs.modal", () => {
      location.reload();
    });
  }
  
  //se llaman las funciones
  cargarGrupos();
  listarReservas();

  //boton para enviar
  document.querySelector("#formReserva").addEventListener("submit", enviarReserva);

  // Observadores - cuando cambia una cosa, se ve que otra no este ocupada
  document.querySelector("#id_gada").addEventListener("change", validarDisponibilidadClases);
  document.querySelector("#fecha_reserva").addEventListener("change", validarDisponibilidadClases);
  document.querySelector("#cantidad_horas").addEventListener("input", generarCamposHoras);
});


// CARGAR GRUPOS ASIGNADOS AL DOCENTE

// Define una función asíncrona (usa await dentro)
async function cargarGrupos() {
  // Obtiene el elemento <select> con id="id_gada"
  const sel = document.querySelector("#id_gada");
  // Limpia cualquier opción previa del select
  sel.innerHTML = "";
  try {

    // Crea un objeto FormData para enviar datos al backend
    const fd = new FormData();

    // Añade el parámetro 'accion' con valor 'cargar_grupos'
    // Esto le indica al backend qué acción debe ejecutar
    fd.append("accion", "cargar_grupos");

    // Envía la solicitud POST al archivo PHP del backend
    const r = await fetch("./../reserva/reserva-accion.php", { method: "POST", body: fd });
    
        // Espera la respuesta y la convierte a formato JSON
    const j = await r.json();


     // Si el backend responde con un tipo distinto de "success",
    // lanza un error que será capturado por el catch
    if (j.type !== "success") throw new Error(j.message || "Error cargando grupos.");

      // Si no hay grupos en los datos devueltos
    if (!j.data.length) {
      sel.innerHTML = `<option>No tienes grupos asignados</option>`;
       // Desactiva el select para que no se pueda usar
      sel.disabled = true;
      return;
    }

    // Si hay grupos, genera las opciones dinámicamente
    // Cada grupo se muestra con nombre_grupo - nombre_asignatura (espacio_base)
    sel.innerHTML = `<option value="">Seleccione</option>` +
      j.data.map(g => `<option value="${g.id_grupo}">${g.nombre_grupo} - ${g.nombre_asignatura} (${g.espacio_base})</option>`).join("");

  } catch (e) {
    sel.innerHTML = `<option>Error cargando grupos</option>`;
    Swal.fire("Error", e.message, "error");
  }
}

//innerHTML lee o escribe el contenido HTML de un elemento

// VALIDAR FECHA Y HABILITAR HORAS

// Función asíncrona para validar si el docente tiene clases el día seleccionado
async function validarDisponibilidadClases() {

   // Obtiene los valores de los campos del formulario
  const id_grupo = document.querySelector("#id_gada").value;
  const fecha = document.querySelector("#fecha_reserva").value;
  const inputHoras = document.querySelector("#cantidad_horas");
  const msg = document.querySelector("#msgHoras");

  inputHoras.value = "";
  inputHoras.disabled = true;
  msg.textContent = "Seleccione primero un grupo y una fecha donde tenga clase.";

  // Reinicia los campos y mensajes al estado inicial
  document.querySelector("#contenedorHorarios").innerHTML = "";
  document.querySelector("#id_espacio").innerHTML = `<option value="">Seleccione fecha y horarios primero</option>`;
  document.querySelector("#id_espacio").disabled = true;


    // Si no hay grupo o fecha seleccionados, termina la función
  if (!id_grupo || !fecha) return;

  // Verifica formato YYYY-MM-DD
  const regexFecha = /^\d{4}-\d{2}-\d{2}$/;
  if (!regexFecha.test(fecha)) {
    msg.textContent = "Ingrese una fecha válida (DD-MM-AAAA).";
    return;
  }

  // No permitir fechas pasadas
  const hoy = new Date(); //feacha actual
  const fechaSel = new Date(fecha + "T00:00:00");  // convierte la fecha seleccionada a objeto Date
  
  
  if (fechaSel < hoy.setHours(0,0,0,0)) { //compara la parte de la fecha, sin hora
    msg.textContent = "No puede reservar en fechas pasadas.";
    return;
  }

  const dia = obtenerDiaSemana(fecha);

  try {

    // Llama a (reserva-accion.php) con acción 'cargar_horas'
    // para saber qué horarios tiene el docente ese día y grupo
    const res = await fetch(`./../reserva/reserva-accion.php?accion=cargar_horas&id_grupo=${id_grupo}&dia=${dia}`);
    const j = await res.json();

    //si el archivo devuelve error, lanza error
    if (j.type !== "success") throw new Error(j.message || "Error al verificar horarios.");

    // Si no hay clases asignadas ese día, muestra mensaje
    if (!j.data.length) {
      msg.textContent = "No tienes clases asignadas ese día con este grupo.";
      return;
    }

    // si hay horarios disponibles - habilitar cantidad_horas
    inputHoras.disabled = false;
    msg.textContent = "";

  } catch (e) { //captura los errores
    msg.textContent = "Error al verificar disponibilidad.";
    Swal.fire("Error", e.message, "error");
  }
}


// GENERAR SELECTS DE HORARIOS

function generarCamposHoras() {
  // Obtiene el contenedor donde se insertarán los selects de horario
  const cont = document.querySelector("#contenedorHorarios");
    // Limpia cualquier contenido previo (por si se cambió el número de horas)
  cont.innerHTML = "";

    // Obtiene la cantidad de horas seleccionadas en el input
  // Si no hay valor, toma "0" como predeterminado
  const n = parseInt(document.querySelector("#cantidad_horas").value || "0");

    // Si n es 0 o no es válido, no genera nada y sale de la función
  if (!n) return;

  for (let i = 1; i <= n; i++) { //bucle para crear los selects de horario
    // Crea un div contenedor para cada select
    const div = document.createElement("div");
    div.className = "mb-2";

    // Inserta el contenido HTML dentro del div:
    // - etiqueta <label> con número de hora
    // - select vacío que se llenará después
    div.innerHTML = `
      <label class="form-label">Hora ${i}</label>
      <select class="form-select selHora" required>
        <option>Seleccione grupo y fecha primero</option>
      </select>`;

       // Agrega este bloque <div> dentro del contenedor principal
    cont.appendChild(div);
  }

  cargarHoras();
}

// CARGAR HORARIOS DEL GRUPO

// Función asincrónica que carga los horarios disponibles según grupo y día
async function cargarHoras() {
  //obtiene id y fecha
  const id_grupo = document.querySelector("#id_gada").value;
  const fecha = document.querySelector("#fecha_reserva").value;
  //si faltan, termina la funcion
  if (!id_grupo || !fecha) return;

  //convierte la fecha en su nombre de dia
  const dia = obtenerDiaSemana(fecha);
  
  try {
    //pide los horarios disponibles de ese grupo y día
    const res = await fetch(`./../reserva/reserva-accion.php?accion=cargar_horas&id_grupo=${id_grupo}&dia=${dia}`);
    const j = await res.json(); //respuesta pasa a ser formato json
    if (j.type !== "success") throw new Error(j.message || "Error al cargar horas."); 

    //genera las option con los horarios recibidos
    const opts = `<option value="">Seleccione</option>` +
      j.data.map(h => `<option value="${h.id_horario_clase}"> ${h.hora_inicio} - ${h.hora_fin} </option>`).join("");

    // Inserta las opciones en todos los selects que tengan la clase .selHora
    document.querySelectorAll(".selHora").forEach(s => s.innerHTML = opts);
    // Habilita el select de espacios
    document.querySelector("#id_espacio").disabled = false;

    cargarEspaciosLibres();
  } catch (e) {
    document.querySelectorAll(".selHora").forEach(s => s.innerHTML = `<option>Error cargando</option>`);
    Swal.fire("Error", e.message, "error");
  }
}



// CARGAR ESPACIOS LIBRES

// Función asíncrona que carga los espacios libres desde el backend
async function cargarEspaciosLibres() {
  // Referencia al select de espacios
  const sel = document.querySelector("#id_espacio");
  //mensaje termporal mientras se cargan los datos 
  sel.innerHTML = `<option>Cargando espacios...</option>`;
  //se desativa el select
  sel.disabled = true;

  try {
    //obtiene el id del grupo
    const id_grupo = document.querySelector("#id_gada").value;
    if (!id_grupo) throw new Error("Seleccione un grupo primero.");

    // Consultar el espacio ya asignado a este grupo
    const resAsignado =    fetch(`./../reserva/reserva-accion.php?accion=espacio_asignado&id_grupo=${id_grupo}`);
    const dataAsignado = await resAsignado.json();
     // Extrae el id del espacio asignado (si existe)
    const id_espacio_asignado = dataAsignado?.id_espacio ?? null;

    // espacios libres
    const fd = new FormData();   // Crea objeto tipo formulario
    fd.append("accion", "cargar_espacios"); // Agrega la acción que el PHP debe ejecutar
    const r = await fetch("./../reserva/reserva-accion.php", { method: "POST", body: fd });  // Convierte la respuesta en JSON
    const j = await r.json();

    if (j.type !== "success") throw new Error(j.message || "Error al cargar espacios.");

    const espacios = j.data.filter(e => e.id_espacio != id_espacio_asignado);
    if (!espacios.length) {
      sel.innerHTML = `<option>No hay espacios libres</option>`;
      return;
    }

    //Generar dinámicamente las opciones del select
    sel.innerHTML = `<option value="">Seleccione un espacio</option>` +
      espacios.map(e =>
        `<option value="${e.id_espacio}">${e.nombre_espacio} (${e.tipo_espacio} - cap. ${e.capacidad_espacio})</option>`
      ).join("");
    // Reactivar el select para permitir la selección
    sel.disabled = false;
  } catch (e) {
    sel.innerHTML = `<option>Error cargando espacios</option>`;
    Swal.fire("Error", e.message, "error");
  }
}

// ENVIAR RESERVA
// Función asíncrona que maneja el envío del formulario de reserva
async function enviarReserva(ev) {
  ev.preventDefault(); // Evita que el formulario recargue la página por defecto

   //Captura de valores del formulario
  const id_grupo = document.querySelector("#id_gada").value;
  const fecha_reserva = document.querySelector("#fecha_reserva").value;
  const dia = fecha_reserva ? obtenerDiaSemana(fecha_reserva) : "";
  const cantidad_horas = parseInt(document.querySelector("#cantidad_horas").value || "0");
  const id_espacio = document.querySelector("#id_espacio").value;
  const observacion = document.querySelector("#observacion")?.value.trim() ?? "";
  const ids_horario = [...document.querySelectorAll(".selHora")].map(s => s.value).filter(Boolean); // // Obtiene todos los selects de hora - Extrae sus valores - Filtra los vacíos


  // Validaciones
  if (!id_grupo) return Swal.fire("Faltan datos", "Debe seleccionar un grupo.", "warning");
  if (!fecha_reserva) return Swal.fire("Faltan datos", "Debe seleccionar una fecha.", "warning");
  if (!cantidad_horas || cantidad_horas < 1) return Swal.fire("Faltan datos", "Indique cuántas horas reservar.", "warning");
  if (ids_horario.length !== cantidad_horas) return Swal.fire("Validación", "Debe seleccionar exactamente las horas indicadas.", "info");
  if (!id_espacio) return Swal.fire("Faltan datos", "Seleccione un espacio.", "warning");

  //validar duplicados
  const duplicados = ids_horario.filter((v, i, arr) => arr.indexOf(v) !== i); // Busca valores repetidos
  
  if (duplicados.length > 0) {
    document.querySelectorAll(".selHora").forEach(sel => {
      if (duplicados.includes(sel.value)) { //Comprueba si el valor seleccionado (sel.value) está dentro del array duplicados
        sel.classList.add("is-invalid"); // Marca visualmente los duplicados con la clase de boostrap
        setTimeout(() => sel.classList.remove("is-invalid"), 2000); //espera dos segundos y lo borra
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
  //envia la informaicon
  fd.append("accion", "crear");
  fd.append("id_grupo", id_grupo);
  fd.append("fecha_reserva", fecha_reserva);
  fd.append("dia", dia);
  fd.append("cantidad_horas", cantidad_horas);
  fd.append("id_espacio", id_espacio);
  fd.append("observacion", observacion);
  //agrega ids en el form data
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


// LISTAR RESERVAS

//// Función asíncrona que lista las reservas del docente
async function listarReservas() {

  const tbody = document.querySelector("#tablaReservas tbody");
  const listaMovil = document.querySelector("#reservasMovil");
  const sinDesktop = document.querySelector("#sinReservasDesktop");
  const sinMovil = document.querySelector("#sinReservasMovil");

  // Limpia contenido previo
  tbody.innerHTML = "";
  listaMovil.innerHTML = "";
  sinDesktop.textContent = "Cargando...";
  sinMovil.textContent = "Cargando...";


  try {
    // Crea FormData para enviar al backend
    const fd = new FormData();
    fd.append("accion", "listar"); // acción = listar reservas

     // Llama al backend con fetch (POST)
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

     // Recorre cada reserva y genera las filas (modo escritorio y móvil)
    j.data.forEach(item => {

      // MODO DESKTOP 
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

      // MODO RESPONSIVE
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
        // Obtiene los datos del botón (id horario y espacio)
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
          // Envía al backend la solicitud de cancelación
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

// COLORES DE ESTADOS

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


// EVITAR REENVÍO AL RECARGAR
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.pathname);
}