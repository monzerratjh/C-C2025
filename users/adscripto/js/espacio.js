// ----------------------------
// ESPACIOS - CRUD COMPLETO
// ----------------------------
document.addEventListener('DOMContentLoaded', () => {

    const formulario = document.getElementById('formularioEspacio');

    // ----------------------------
    // TIPO YA DETECTADO POR PHP
    // ----------------------------
    const tipoDetectado = document.getElementById('tipo_espacio').value;

    // ----------------------------
    // VALIDACIÓN DEL FORMULARIO
    // ----------------------------
    function validarEspacio() {
        const nombre = document.getElementById('nombre_espacio').value.trim();
        const capacidad = parseInt(document.getElementById('capacidad_espacio').value);
        const disponibilidad = document.getElementById('disponibilidad_espacio').value.trim();

        if (nombre === '') {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese el nombre del espacio.' });
            return false;
        }

        if (isNaN(capacidad) || capacidad < 1 || capacidad > 100) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Capacidad inválida. Debe ser entre 1 y 100.' });
            return false;
        }

        if (disponibilidad === '') {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Seleccione el estado de disponibilidad.' });
            return false;
        }

        return true; // Todo correcto
    }

    // ----------------------------
    // OBTENER DATOS DEL FORMULARIO
    // ----------------------------
    function obtenerDatosEspacio() {
        const formData = new FormData(formulario);

        // Asegurar consistencia con validación
        formData.set("nombre_espacio", document.getElementById('nombre_espacio').value.trim());
        formData.set("tipo_espacio", tipoDetectado); // usar el valor de PHP
        formData.set("capacidad_espacio", document.getElementById('capacidad_espacio').value);
        formData.set("disponibilidad_espacio", document.getElementById('disponibilidad_espacio').value.trim());
        formData.set("historial_espacio", document.getElementById('historial_espacio').value.trim());

        return formData;
    }

    // ----------------------------
    // ENVÍO AL PHP
    // ----------------------------
    function enviarEspacio(formData) {
        fetch('espacio-accion.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire({
                icon: data.type,
                title: data.type === "error" ? "Error" : "Éxito",
                text: data.message
            }).then(() => {
                if (data.type === 'success') location.reload();
            });
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo procesar la solicitud.'
            });
            console.error('Error al enviar espacio:', err);
        });
    }

    // ----------------------------
    // EVENTO SUBMIT
    // ----------------------------
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validarEspacio()) {
            const formData = obtenerDatosEspacio();
            enviarEspacio(formData);
        }
    });

    // ----------------------------
    // NUEVO ESPACIO
    // ----------------------------
    window.prepararNuevoEspacio = () => {
        formulario.reset();
        document.getElementById('accion').value = 'insertar';
        document.getElementById('id_espacio').value = '';
    };

    // ----------------------------
    // EDITAR ESPACIO
    // ----------------------------
    window.cargarEditarEspacio = (espacio) => {
        document.getElementById('id_espacio').value = espacio.id_espacio;
        document.getElementById('nombre_espacio').value = espacio.nombre_espacio;
        document.getElementById('capacidad_espacio').value = espacio.capacidad_espacio;
        document.getElementById('disponibilidad_espacio').value = espacio.disponibilidad_espacio;
        document.getElementById('historial_espacio').value = espacio.historial_espacio;
        document.getElementById('accion').value = 'editar';
    };

    // ----------------------------
    // ELIMINAR ESPACIO
    // ----------------------------
    document.addEventListener('click', function (e) {
        if (e.target.closest('.eliminar-espacio-boton')) {
            const boton = e.target.closest('.eliminar-espacio-boton');
            const id = boton.dataset.id;

            Swal.fire({
                title: '¿Eliminar espacio?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = new FormData();
                    form.append("accion", "eliminar");
                    form.append("id_espacio", id);
                    enviarEspacio(form);
                }
            });
        }
    });

    // ----------------------------
    // EVITAR REENVÍO AL RECARGAR
    // ----------------------------
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.pathname);
    }

});
