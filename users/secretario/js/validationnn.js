document.addEventListener('DOMContentLoaded', function(){
    
    // Asignar evento a TODOS los formularios que tengan action a editar-usuario.php
    const forms = document.querySelectorAll('form[id^="editarUsuarioForm"]');
    console.log("Formularios de edición encontrados:", forms.length);

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if(validarEdicionUsuario(this, false)) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas guardar los cambios?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        })
    } );
});

function validarEdicionUsuario(form) {
    console.log("🔍 === INICIANDO VALIDACIÓN ===");
    console.log("🔍 Formulario:", form);
    console.log("🔍 ID del formulario:", form.id);
    

    // Verificar SI el formulario tiene los campos
    console.log("🔍 Buscando campo cargo_usuario...");
    const cargoElement = form.querySelector('[name="cargo_usuario"]');
    console.log("🔍 Elemento cargo encontrado:", cargoElement);

       
    if (!cargoElement) {
        console.error("❌ NO SE ENCONTRÓ cargo_usuario");
        console.log("🔍 Todos los elementos con name:", form.querySelectorAll('[name]'));
        return false;
    }

    let ci_usr_edit = form.querySelector('[name="ci_usuario"]').value;
    let nombre_usr_edit = form.querySelector('[name="nombre_usuario"]').value.trim();
    let apellido_usr_edit = form.querySelector('[name="apellido_usuario"]').value.trim();
    let gmail_usr_edit = form.querySelector('[name="gmail_usuario"]').value.trim();
    let telefono_usr_edit = form.querySelector('[name="telefono_usuario"]').value;
    let cargo_usr_edit = form.querySelector('select[name="cargo_usuario"]');
    let contrasenia_usr_edit = form.querySelector('[name="contrasenia_usuario"]').value;

     console.log("Validando formulario:", {ci_usr_edit, cargo_usr_edit});

    if(!ci_usr_edit || !nombre_usr_edit || !apellido_usr_edit || !gmail_usr_edit || !telefono_usr_edit) {
        Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Todos los campos son obligatorios',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    if(ci_usr_edit.length == 0 || isNaN(ci_usr_edit)) {
        Swal.fire({
            icon: 'error',
            title:'Cédula inválida',
            text: 'La cédula debe ser un número válido',
            confirmButtonColor: '#d33'
        });
        return false;

    } else if(ci_usr_edit.length !== 8) {
        Swal.fire({
            icon:'error',
            title: 'Cédula inválida',
            text: 'La cédula debe tener 8 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    if(telefono_usr_edit.length === 0 || isNaN(telefono_usr_edit)) {
        Swal.fire({
            icon: 'error',
            title: 'Teléfono inválido',
            text: 'El teléfono debe ser un número válido',
            confirmButtonColor: '#d33'
        });
        return false;

    } else if(telefono_usr_edit.length !== 9) {
        Swal.fire({
            icon: 'error',
            title: 'Teléfono inválido',
            text: 'El teléfono debe tener 9 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;

    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(gmail_usr_edit)) {
        Swal.fire({
            icon: 'error',
            title: 'Email inválido',
            text: 'Por favor ingrese un email válido',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    if(contrasenia_usr_edit && contrasenia_usr_edit.length > 0) {
        if(contrasenia_usr_edit.length < 8 || contrasenia_usr_edit.length > 20) {
            Swal.fire({
                icon: 'error',
                title: 'Contraseña inválida',
                text: 'La contraseña debe tener entre 8 y 20 caracteres',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        const tieneMayus = /[A-Z]/.test(contrasenia_usr_edit);
        const tieneMinus = /[a-z]/.test(contrasenia_usr_edit);
        const tieneNumero = /[0-9]/.test(contrasenia_usr_edit);

        if(!tieneMayus || !tieneMinus || !tieneNumero) {
            Swal.fire({
                icon: 'error',
                title: 'Contraseña inválida',
                html: 'La contraseña debe tener:<br>' +
                      '• Al menos una MAYÚSCULA<br>' +
                      '• Al menos una minúscula<br>' +
                      '• Al menos un número',
                confirmButtonColor: '#d33'
            });
            return false;
        }
    }

     return true;
}


/*const modalEdicion = document.getElementById('update_modal');
const editarUsuarioForm = document.getElementById('editarUsuarioForm');

function validarEdicionUsuario() {
    let ci_usr_edit = document.getElementById('ci_usuario_edit').value;
    let nombre_usr_edit = document.getElementById('nombre_usuario_edit').value.trim();
    let apellido_usr_edit = document.getElementById('apellido_usuario_edit').value.trim();
    let gmail_usr_edit = document.getElementById('gmail_usuario_edit').value.trim();
    let telefono_usr_edit = document.getElementById('telefono_usuario_edit').value;
    let cargo_usr_edit = document.getElementById('cargo_usuario_edit').value;
    let contrasenia_usr_edit = document.getElementById('contrasenia_usuario_edit').value;

     console.log("Validando formulario:", {ci_usr_edit, cargo_usr_edit});

    if(!ci_usr_edit || !nombre_usr_edit || !apellido_usr_edit || !gmail_usr_edit || !telefono_usr_edit) {
        Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Todos los campos son obligatorios',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    if(ci_usr_edit.length == 0 || isNaN(ci_usr_edit)) {
        Swal.fire({
            icon: 'error',
            title:'Cédula inválida',
            text: 'La cédula debe ser un número válido',
            confirmButtonColor: '#d33'
        });
        return false;

    } else if(ci_usr_edit.length !== 8) {
        Swal.fire({
            icon:'error',
            title: 'Cédula inválida',
            text: 'La cédula debe tener 8 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    if(telefono_usr_edit.length === 0 || isNaN(telefono_usr_edit)) {
        Swal.fire({
            icon: 'error',
            title: 'Teléfono inválido',
            text: 'El teléfono debe ser un número válido',
            confirmButtonColor: '#d33'
        });
        return false;

    } else if(telefono_usr_edit.length !== 9) {
        Swal.fire({
            icon: 'error',
            title: 'Teléfono inválido',
            text: 'El teléfono debe tener 9 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;

    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(gmail_usr_edit)) {
        Swal.fire({
            icon: 'error',
            title: 'Email inválido',
            text: 'Por favor ingrese un email válido',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    if(contrasenia_usr_edit && contrasenia_usr_edit.length > 0) {
        if(contrasenia_usr_edit.length < 8 || contrasenia_usr_edit.length > 20) {
            Swal.fire({
                icon: 'error',
                title: 'Contraseña inválida',
                text: 'La contraseña debe tener entre 8 y 20 caracteres',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        const tieneMayus = /[A-Z]/.test(contrasenia_usr_edit);
        const tieneMinus = /[a-z]/.test(contrasenia_usr_edit);
        const tieneNumero = /[0-9]/.test(contrasenia_usr_edit);

        if(!tieneMayus || !tieneMinus || !tieneNumero) {
            Swal.fire({
                icon: 'error',
                title: 'Contraseña inválida',
                html: 'La contraseña debe tener:<br>' +
                      '• Al menos una MAYÚSCULA<br>' +
                      '• Al menos una minúscula<br>' +
                      '• Al menos un número',
                confirmButtonColor: '#d33'
            });
            return false;
        }
    }

     return true;
}

function manejarEnvioFormulario(event) {
    event.preventDefault(); // Prevenir envío por defecto
    
    if(validarEdicionUsuario()) {
        // Mostrar confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas guardar los cambios?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar formulario
                editarUsuarioForm.submit();
            }
        });
    }
}

// Asignar evento al formulario
if (editarUsuarioForm) {
    editarUsuarioForm.addEventListener('submit', manejarEnvioFormulario);
}*/
