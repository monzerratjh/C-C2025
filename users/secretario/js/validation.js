document.addEventListener('DOMContentLoaded', function(){
    
    // ========== FORMULARIOS DE EDICIÓN ==========
    const formsEdicion = document.querySelectorAll('form[id^="editarUsuarioForm"]');
    console.log("✅ Formularios de EDICIÓN encontrados:", formsEdicion.length);

    formsEdicion.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const modal = this.closest('.modal');
            const estaVisible = modal && modal.classList.contains('show');
            console.log("🔍 Modal visible:", estaVisible);
            console.log("🔍 Formulario HTML:", this.outerHTML.substring(0, 200));
            console.log("Submit para:", this.id);

            if(validarFormulario(this, false)) {
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
    });

    // ========== CREACIÓN CON JS DE MIERDA ==========
    const formCreacion = document.querySelector('form[action="./agregar-usuario.php"]');
    
    if(formCreacion) {
        console.log("✅ Formulario de CREACIÓN encontrado");
        
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

function validarFormulario(form, contraseniaObligatoria) {
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
    if(contraseniaObligatoria && !contrasenia) {
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