
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