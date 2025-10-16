document.addEventListener('DOMContentLoaded', () => {

//Validaciones de login (solo si existe el formulario)
  const domForm = document.getElementById('form-login');
  if (domForm) {
    domForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const cedula = document.getElementById('cedula').value.trim();
      const password = document.getElementById('password').value.trim();

      if (!cedula || !password) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Todos los campos son obligatorios' });
        return;
      }

      if (isNaN(cedula)){
        Swal.fire({ icon: 'error', title: 'Error', text: 'La cédula deben ser solo números' });
        return;
      }

      if (cedula.length !== 8){
        Swal.fire({ icon: 'error', title: 'Error', text: 'La cédula debe tener 8 números válidos' });
        return;
      }

      //contraseña usuario = Base de Datos? (Sabri va a entender)

        /*    // Validación de contraseña 
        if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>_]).{6,}$/.test(password)) {
        Swal.fire({
        icon: 'error',
        title: 'Contraseña Incorrecta',
        text: 'Debe tener mínimo 8 caracteres, al menos una mayúscula, una minúscula, un número y un carácter especial'
    }); */

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
  }

  const cerrarSesionBtns = document.querySelectorAll('.btn-cerrar-sesion');
  cerrarSesionBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      Swal.fire({
        title: '¿Seguro que deseas cerrar sesión?',
        text: "Se cerrará tu sesión actual",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = './../../index.php';
        }
      });
    });
  });
});
