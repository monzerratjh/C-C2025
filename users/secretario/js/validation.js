document.addEventListener('DOMContentLoaded', function(){
    
    const formsEdicion = document.querySelectorAll('form[id^="editarUsuarioForm"]');
    console.log("Formularios de EDICIÓN encontrados:", formsEdicion.length);

    formsEdicion.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const modal = this.closest('.modal');
            const estaVisible = modal && modal.classList.contains('show');
            console.log("Modal visible:", estaVisible);
            console.log("Submit para:", this.id);


            if(validarFormularioEdicion(this)) {
                
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
        });
    }


);





















































/*
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.matches('form[id^="editarUsuarioForm"]')) {
        e.preventDefault();

        const modal = form.closest('.modal');
        const estaVisible = modal && modal.classList.contains('show');
        console.log("🚀 Submit detectado para:", form.id);
        console.log("Modal visible:", estaVisible);

        if (validarFormularioEdicion(form)) {
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
                    form.submit();
                }
            });
        }
    }
});

*/




























    // ========== CREACIÓN CON JS==========
    const formCreacion = document.querySelector('[action="./agregar-usuario.php"]');
    
    if(formCreacion) {
        console.log("Formulario de CREACIÓN encontrado");
        
        formCreacion.addEventListener('submit', function(e) {
            e.preventDefault();

            if(validarFormulario(this, true)) {
                Swal.fire({
                    title: '¿Crear usuario?',
                    text: "Se agregará un nuevo usuario",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, crear',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        });
    }
});

function validarFormulario(form) {
    // Obtener valores directamente
    const ci = form.querySelector('[name="ci_usuario"]')?.value.trim() || '';
    const nombre = form.querySelector('[name="nombre_usuario"]')?.value.trim() || '';
    const apellido = form.querySelector('[name="apellido_usuario"]')?.value.trim() || '';
    const gmail = form.querySelector('[name="gmail_usuario"]')?.value.trim() || '';
    const telefono = form.querySelector('[name="telefono_usuario"]')?.value.trim() || '';
    const cargo = form.querySelector('[name="cargo_usuario"]')?.value || '';
    const contrasenia = form.querySelector('[name="contrasenia_usuario"]')?.value || '';

    console.log("Valores encontrados:", {ci, nombre, apellido, gmail, telefono, cargo, contraseniaVacia: !contrasenia});

    // Validar campos obligatorios
    if(!ci || !nombre || !apellido || !gmail || !telefono || !cargo) {
        Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Todos los campos son obligatorios',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    // Validar cédula
    if(isNaN(ci) || ci.length !== 8) {
        Swal.fire({
            icon: 'error',
            title: 'Cédula inválida',
            text: 'La cédula debe tener 8 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    // Validar teléfono
    if(isNaN(telefono) || telefono.length !== 9) {
        Swal.fire({
            icon: 'error',
            title: 'Teléfono inválido',
            text: 'El teléfono debe tener 9 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(gmail)) {
        Swal.fire({
            icon: 'error',
            title: 'Email inválido',
            text: 'Por favor ingrese un email válido',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    // Validar contraseña (solo si es obligatoria o si se ingresó alguna)
    if(!contrasenia) {
        Swal.fire({
            icon: 'error',
            title: 'Contraseña requerida',
            text: 'Debe ingresar una contraseña',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    if(contrasenia && contrasenia.length > 0) {
        if(contrasenia.length < 8 || contrasenia.length > 20) {
            Swal.fire({
                icon: 'error',
                title: 'Contraseña inválida',
                text: 'La contraseña debe tener entre 8 y 20 caracteres',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        const tieneMayus = /[A-Z]/.test(contrasenia);
        const tieneMinus = /[a-z]/.test(contrasenia);
        const tieneNumero = /[0-9]/.test(contrasenia);

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

    console.log("✅ Validación exitosa");
    return true;
}

function validarFormularioEdicion( form, esCreacion = false) {
    const ci_ed= form.querySelector('[name="ci_usuario"]')?.value.trim() || '';
    const nombre_ed = form.querySelector('[name="nombre_usuario"]')?.value.trim() || '';
    const apellido_ed = form.querySelector('[name="apellido_usuario"]')?.value.trim() || '';
    const gmail_ed = form.querySelector('[name="gmail_usuario"]')?.value.trim() || '';
    const telefono_ed = form.querySelector('[name="telefono_usuario"]')?.value.trim() || '';
    const cargo_ed = form.querySelector('[name="cargo_usuario"]')?.value || '';
    const contrasenia_ed = form.querySelector('[name="contrasenia_usuario"]')?.value || '';

    console.log("Valores encontrados para editar:", {ci_ed, nombre_ed, apellido_ed, gmail_ed, telefono_ed, cargo_ed, contrasenia_ed});

    // Validar campos obligatorios
    if(!ci_ed || !nombre_ed || !apellido_ed || !gmail_ed || !telefono_ed || !cargo_ed) {
        Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Todos los campos son obligatorios',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    // Validar cédula
    if(isNaN(ci_ed) || ci_ed.length !== 8) {
        Swal.fire({
            icon: 'error',
            title: 'Cédula inválida',
            text: 'La cédula debe tener 8 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    // Validar teléfono
    if(isNaN(telefono_ed) || telefono_ed.length !== 9) {
        Swal.fire({
            icon: 'error',
            title: 'Teléfono inválido',
            text: 'El teléfono debe tener 9 dígitos',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(gmail_ed)) {
        Swal.fire({
            icon: 'error',
            title: 'Email inválido',
            text: 'Por favor ingrese un email válido',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    console.log("✅ Validación de edición exitosa");
    return true;
}

