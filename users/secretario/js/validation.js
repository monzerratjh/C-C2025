const form = document.querySelector('form');
const ci_usuario = document.getElementById('ci_usuario');
const nombre_usuario = document.getElementById('nombre_usuario');
const apellido_usuario = document.getElementById('apellido_usuario');
const telefono_usuario = document.getElementById('telefono_usuario');
const gmail_usuario = document.getElementById('gmail_usuario');
const contrasenia_usuario = document.getElementById('contrasenia_usuario');


function validacionCampos() {
    if (ci_usuario.value.trim() == "" || 
        contrasenia_usuario.value.trim() == "" || 
        nombre_usuario.value.trim() == "" || 
        apellido_usuario.value.trim() == "" || 
        telefono_usuario.value.trim() == "" || 
        gmail_usuario.value.trim() == "") {
        alert("Por favor, complete todos los campos");
        return false;
    }
    return true;
}


function validacionLongitud(ci_usuario, telefono_usuario) {
    if (ci_usuario.value.trim().length !== 8 || isNaN(ci_usuario.value.trim())) {
        alert("La cédula debe tener 8 números válidos");
        return false;
    } else if(telefono_usuario.value.trim().length !== 9 || isNaN(telefono_usuario.value.trim())) {
        alert("El teléfono debe tener 9 números válidos");
        return false;
    }
    return true;
}


function validarConstrasenia (contrasenia_usuario){
    if (contrasenia_usuario.value.trim().length < 5) {
        alert("La contraseña debe tener al menos 5 caracteres.");
            return false; // Evitar el envío del formulario
        }
        if (!/[A-Z]/.test(contrasenia_usuario.value.trim()) || !/[0-9]/.test(contrasenia_usuario.value.trim())) {
        alert("La contraseña debe contener al menos una letra mayúscula y un número.");
            return false; // Evitar el envío del formulario
        }
    return true;
}


form.addEventListener('submit', function(e) {
    if( !validacionCampos() ||
        !validacionLongitud(ci_usuario, telefono_usuario) ||
        !validarConstrasenia(contrasenia_usuario)) {
        e.preventDefault();
    }
});


    // GRUPOS

// ----------------------------
// VALIDACIÓN DEL FORMULARIO GRUPOS
// ----------------------------
function validarGrupo() {
    const nombre = document.getElementById('nombre').value.trim();
    const orientacion = document.getElementById('orientacionInput').value.trim();
    const turno = document.getElementById('turno').value;
    const cantidad = parseInt(document.getElementById('cantidad').value);

    if (nombre === '') {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'Ingrese el nombre del grupo' });
        return false;
    } else if (nombre.length > 6) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'El nombre del grupo debe ser menor a 6 caracteres' });
        return false;
    }

    if (orientacion === '') {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'La orientación debe coincidir con una de las opciones del sistema.' });
        return false;
    }

    if (turno === '') {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'Seleccione un turno' });
        return false;
    }

    if (isNaN(cantidad) || cantidad < 1 || cantidad > 50 ) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'Cantidad de alumnos inválida. Debe ser entre 1 y 50' });
        return false;
    }

    return true; // Todo bien
}

// ----------------------------
// OBTENER DATOS DEL FORMULARIO
// ----------------------------
function obtenerDatosGrupo() {
    const form = document.getElementById('formGrupo');
    const formData = new FormData(form);

    // Asegurarse de que los valores sean los mismos que validamos
    formData.set("nombre", document.getElementById('nombre').value.trim());
    formData.set("orientacion", document.getElementById('orientacionInput').value.trim());
    formData.set("turno", document.getElementById('turno').value);
    formData.set("cantidad", document.getElementById('cantidad').value);

    return formData;
}

// ----------------------------
// ENVÍO DE DATOS AL PHP
// ----------------------------
function enviarGrupo(formData) {
    fetch("grupo-accion.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.type,
            title: data.type === "error" ? "Error" : "Éxito",
            text: data.message
        }).then(() => {
            if (data.type === 'success') {
                location.reload(); // recarga la lista SOLO si todo salió bien
            }
        });
    })
    .catch(err => {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'No se pudo procesar la solicitud' });
        console.error(err);
    });
}

// ----------------------------
// EVENTO SUBMIT FORMULARIO GRUPOS
// ----------------------------
document.getElementById('formGrupo').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita envío directo

    if (validarGrupo()) {
        const formData = obtenerDatosGrupo();
        enviarGrupo(formData);
    }
});

// ----------------------------
// ELIMINAR GRUPO
// ----------------------------
document.addEventListener('click', function(e) {
    if (e.target.matches('.eliminar-grupo-btn')) {
        const id = e.target.dataset.id;
        Swal.fire({
            title: '¿Eliminar grupo?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = new FormData();
                form.append("accion", "eliminar");
                form.append("id_grupo", id);
                enviarGrupo(form);
            }
        });
    }
});

    // HORARIO
// ----------------------------
// VALIDAR FORMULARIO HORARIOS
// ----------------------------
function validarHorario() {
    const dia = document.getElementById('dia').value;
    const hora_inicio = document.getElementById('hora_inicio').value;
    const hora_fin = document.getElementById('hora_fin').value;
    const turno = document.getElementById('turno').value;

    if (dia === '') { 
        Swal.fire({ icon: 'error', title: 'Error', text: 'Seleccione un día' }); 
        return false; 
    }
    if (hora_inicio === '') { 
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese la hora de inicio de las clases' }); 
        return false; 
    }
    if (hora_fin === '') { 
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese la hora de finalización de las clases' }); 
        return false; 
    }
    if (hora_inicio >= hora_fin) { 
        Swal.fire({ icon: 'error', title: 'Error', text: 'La hora de inicio debe ser menor que la hora de finalización.' }); 
        return false; 
    }    
    if (turno === '') {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Seleccione un turno' });
        return false;
    }

    return true;
}

// ----------------------------
// OBTENER DATOS DEL FORMULARIO
// ----------------------------
function obtenerDatosHorario() {
    const formH = document.getElementById('formHorario');
    const formDataH = new FormData(formH); // captura todos los inputs automáticamente

    // Asegurarse que campos clave estén correctos
    formDataH.set("dia", document.getElementById('dia').value.trim());
    formDataH.set("hora_inicio", document.getElementById('hora_inicio').value.trim());
    formDataH.set("hora_fin", document.getElementById('hora_fin').value.trim());
    formDataH.set("turno", document.getElementById('turno').value);

    // console.log para debug
    console.log([...formDataH.entries()]);

    return formDataH;
}

// ----------------------------
// ENVÍO DE DATOS AL PHP
// ----------------------------
function enviarHorario(formData) {
    fetch("horario-accion.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.type,
            title: data.type === "error" ? "Error" : "Éxito",
            text: data.message
        }).then(() => {
            if (data.type === 'success') {
                location.reload(); // recarga la lista SOLO si todo salió bien
            }
        });
    })
    .catch(err => {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo procesar la solicitud' });
        console.error(err);
    });
}

// ----------------------------
// SUBMIT FORMULARIO HORARIOS
// ----------------------------
document.getElementById('formHorario').addEventListener('submit', function(e) {
    e.preventDefault(); // evita envío directo

    if (validarHorario()) {
        const formDataH = obtenerDatosHorario();
        enviarHorario(formDataH);
    }
});

// ----------------------------
// ELIMINAR HORARIO
// ----------------------------
document.addEventListener('click', function(e){
    // Tomar el botón correcto, incluso si se clickea el <i> dentro del botón
    const boton = e.target.closest('.eliminar-horario-btn');
    if (boton) {
        const id = boton.dataset.id;
        Swal.fire({
            title: '¿Desea eliminar este horario?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed){
                const form = new FormData();
                form.append('accionHorario', 'eliminar');
                form.append('id_horario_clase', id);
                enviarHorario(form);
            }
        });
    }
});

// ----------------------------
// CARGAR HORARIO PARA EDITAR
// ----------------------------
function cargarEditarHorario(id, dia, inicio, fin, turno) {
    document.getElementById('accionHorario').value = 'editar';
    document.getElementById('id_horario_clase').value = id;
    document.getElementById('dia').value = dia;
    document.getElementById('hora_inicio').value = inicio;
    document.getElementById('hora_fin').value = fin;
    document.getElementById('turno').value = turno;
}

// ----------------------------
// Evitar reenvío al recargar
// ----------------------------
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.pathname);
}