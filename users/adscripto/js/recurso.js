
function cargarEditarRecurso(idRecurso, nombreRecurso, tipoRecurso, disponibilidadRecurso, estadoRecurso, historialRecurso) {
    document.getElementById('accion_recurso').value = 'editar';
    document.getElementById('id_recurso').value = idRecurso;
    document.getElementById('nombre_recurso').value = nombreRecurso;
    document.getElementById('tipo_recurso').value = tipoRecurso;
    document.getElementById('disponibilidad_recurso').value = disponibilidadRecurso;
    document.getElementById('estado_recurso').value = estadoRecurso;
    document.getElementById('historial_recurso').value = historialRecurso;
}

function prepararNuevoRecurso() {
    document.getElementById('accion_recurso').value = 'insertar';
    document.getElementById('id_recurso').value = '';
    document.getElementById('formularioRecurso').reset();
}

function validarFormularioRecurso() {
    const nombre = document.getElementById('nombre_recurso').value.trim();
    const tipo = document.getElementById('tipo_recurso').value.trim();
    const estado = document.getElementById('estado_recurso').value;

    if (!nombre || nombre.length < 2) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese un nombre de recurso válido.' });
        return false;
    }
    if (!tipo) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese el tipo de recurso.' });
        return false;
    }
    if (!estado) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Seleccione el estado del recurso.' });
        return false;
    }
    return true;
}

function obtenerDatosFormularioRecurso() {
    const formulario = document.getElementById('formularioRecurso');
    const datos = new FormData(formulario);
    datos.set('nombre_recurso', document.getElementById('nombre_recurso').value.trim());
    datos.set('tipo_recurso', document.getElementById('tipo_recurso').value.trim());
    datos.set('disponibilidad_recurso', document.getElementById('disponibilidad_recurso').value.trim());
    datos.set('estado_recurso', document.getElementById('estado_recurso').value);
    datos.set('historial_recurso', document.getElementById('historial_recurso').value.trim());
    return datos;
}

function enviarDatosRecurso(datosFormulario) {
    fetch('recurso-accion.php', {
        method: 'POST',
        body: datosFormulario
    })
    .then(function(respuesta) { return respuesta.json(); })
    .then(function(datos) {
        Swal.fire({
            icon: datos.type,
            title: datos.type === 'error' ? 'Error' : 'Éxito',
            text: datos.message
        }).then(function() {
            if (datos.type === 'success') {
                location.reload();
            }
        });
    })
    .catch(function(error) {
        console.error(error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo procesar la solicitud.' });
    });
}

document.getElementById('formularioRecurso').addEventListener('submit', function(evento) {
    evento.preventDefault();
    if (validarFormularioRecurso()) {
        const datos = obtenerDatosFormularioRecurso();
        enviarDatosRecurso(datos);
    }
});

document.addEventListener('click', function(evento) {
    if (evento.target && evento.target.matches('.eliminar-recurso-boton')) {
        const idRecurso = evento.target.dataset.id;
        Swal.fire({
            title: '¿Eliminar recurso?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(resultado) {
            if (resultado.isConfirmed) {
                const datos = new FormData();
                datos.append('accion', 'eliminar');
                datos.append('id_recurso', idRecurso);
                enviarDatosRecurso(datos);
            }
        });
    }
});

if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.pathname);
}
