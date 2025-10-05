document.addEventListener('DOMContentLoaded', () => {
    const domForm = document.getElementById('form-login');

    domForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const cedula = document.getElementById('cedula').value.trim();
        const password = document.getElementById('password').value.trim();

         // Validación de campos vacíos
        if (!cedula || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Todos los campos son obligatorios'
            });
            return;
        }

        if (cedula <7 && cedula >8){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La cédula debe tener 8 números válidos'
        });
            return;
        }

         if (cedula.length !== 8){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La cédula debe tener 8 números válidos'
        });
            return;
        }

            // Validación de contraseña segura
        if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>_]).{6,}$/.test(password)) {
        Swal.fire({
        icon: 'error',
        title: 'Contraseña insegura',
        text: 'Debe tener mínimo 8 caracteres, al menos una mayúscula, una minúscula, un número y un carácter especial'
    });
    return;
}
//.test() es un método de los objetos RegExp (expresiones regulares).
//(?=...) -> lookahead positivo, significa "la cadena debe contener esto en algún lugar".
//el . indica que puede haber cualquier carácter antes o después de los requisitos
//(?=.*\d) -> \d = cualquier dígito (0-9)
//(?=.*[!@#$%^&*(),.?":{}|<>]) -> asegura que haya al menos un carácter especial
//{8,} -> al menos 8 caracteres de longitud

        // Si todas las validaciones pasan, se envía el formulario usando fetch

        const formData = new FormData(this);

        fetch(this.action, {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                // Mensaje de éxito antes de redirigir
                Swal.fire({
                    icon: 'success',
                    title: '¡Bienvenido!',
                    text: 'Inicio de sesión exitoso',
                    timer: 2000, //se cierra despues de 2 segundos
                    showConfirmButton: false //oculta el boton por defecto
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                // Mostrar mensaje de error con SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })

// .catch() se utiliza para capturar cualquier error que ocurra en la promesa.
// En este caso, captura errores que puedan ocurrir al enviar el formulario con fetch,
// como problemas de conexión, servidor caído o URL incorrecta.

        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al conectar con el servidor.'
            });
        });
    });
});