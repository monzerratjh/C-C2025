
function cargarEditarEspacio(id, nombre, capacidad, disponibilidad, historial, tipo) {
  document.getElementById('accion').value = 'editar';
  document.getElementById('id_espacio').value = id;
  document.getElementById('nombre_espacio').value = nombre;
  document.getElementById('capacidad_espacio').value = capacidad;
  document.getElementById('disponibilidad_espacio').value = disponibilidad;
  document.getElementById('historial_espacio').value = historial;
  document.getElementById('tipo_espacio').value = tipo; 
}


function prepararNuevoEspacio() {
    document.getElementById('accion').value = 'insertar';
    document.getElementById('id_espacio').value = '';
    document.getElementById('formularioEspacio').reset();
}

function validarFormularioEspacio() {
    const nombre = document.getElementById('nombre_espacio').value.trim();
    const capacidad = parseInt(document.getElementById('capacidad_espacio').value, 10);
    const disponibilidad = document.getElementById('disponibilidad_espacio').value;

    if (!nombre || nombre.length < 2) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese un nombre válido (mínimo 2 caracteres).' });
        return false;
    }
    if (isNaN(capacidad) || capacidad < 1 || capacidad > 500) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'La capacidad debe ser un número entre 1 y 500.' });
        return false;
    }
    if (!disponibilidad) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Seleccione la disponibilidad.' });
        return false;
    }
    return true;
}

function obtenerDatosFormularioEspacio() {
    const formulario = document.getElementById('formularioEspacio');
    const datosFormulario = new FormData(formulario);

    datosFormulario.set('nombre_espacio', document.getElementById('nombre_espacio').value.trim());
    datosFormulario.set('capacidad_espacio', document.getElementById('capacidad_espacio').value);
    datosFormulario.set('disponibilidad_espacio', document.getElementById('disponibilidad_espacio').value);
    datosFormulario.set('historial_espacio', document.getElementById('historial_espacio').value.trim());

    return datosFormulario;
}

function enviarDatosEspacio(datosFormulario) {
    fetch('espacio-accion.php', {
        method: 'POST',
        body: datosFormulario
    })
    .then(function(respuesta) {
        return respuesta.json();
    })
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

// Evento submit del formulario
document.getElementById('formularioEspacio').addEventListener('submit', function(evento) {
    evento.preventDefault();
    if (validarFormularioEspacio()) {
        const datos = obtenerDatosFormularioEspacio();
        enviarDatosEspacio(datos);
    }
});

// Eliminar espacio
document.addEventListener('click', function(evento) {
    if (evento.target && evento.target.matches('.eliminar-espacio-boton')) {
        const idEspacio = evento.target.dataset.id;
        Swal.fire({
            title: '¿Eliminar espacio?',
            text: 'Esta acción no se puede deshacer. Revise dependencias antes de confirmar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(resultado) {
            if (resultado.isConfirmed) {
                const datos = new FormData();
                datos.append('accion', 'eliminar');
                datos.append('id_espacio', idEspacio);
                enviarDatosEspacio(datos);
            }
        });
    }
});

// Evitar reenvío del formulario al recargar la página
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.pathname);
}
