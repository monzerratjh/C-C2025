// espacio.js

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('formularioEspacio');
    const accionInput = document.getElementById('accion');
    const idInput = document.getElementById('id_espacio');
    const nombreInput = document.getElementById('nombre_espacio');
    const tipoSelect = document.getElementById('tipo_espacio');
    const capacidadInput = document.getElementById('capacidad_espacio');
    const disponibilidadSelect = document.getElementById('disponibilidad_espacio');
    const historialTextarea = document.getElementById('historial_espacio');

    // Función para preparar modal para nuevo espacio
    window.prepararNuevoEspacio = function() {
        accionInput.value = 'insertar';
        idInput.value = '';
        nombreInput.value = '';
        tipoSelect.value = '';
        capacidadInput.value = '';
        disponibilidadSelect.value = '';
        historialTextarea.value = '';
    }

    // Función para cargar modal para editar
    window.cargarEditarEspacio = function(id, nombre, tipo, capacidad, disponibilidad, historial) {
        accionInput.value = 'editar';
        idInput.value = id;
        nombreInput.value = nombre;
        tipoSelect.value = tipo;
        capacidadInput.value = capacidad;
        disponibilidadSelect.value = disponibilidad;
        historialTextarea.value = historial;
    }

    // Enviar formulario vía AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('espacio-accion.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.type === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // recarga la página para actualizar listado
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud.'
            });
        });
    });

    // Botones de eliminar
    document.querySelectorAll('.eliminar-espacio-boton').forEach(btn => {
    btn.addEventListener('click', function() {
        console.log("Click eliminar ID:", this.dataset.id);

          const idEspacio = this.dataset.id;

            Swal.fire({
                title: '¿Eliminar espacio?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('accion', 'eliminar');
                    formData.append('id_espacio', idEspacio);

                    fetch('espacio-accion.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.type === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud.'
                        });
                    });
                }
            });
        });
    });

});
