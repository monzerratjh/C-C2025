document.addEventListener('DOMContentLoaded', () => {

//Validaciones de login (solo si existe el formulario)
  const domForm = document.getElementById('form-login');
  if (domForm) {
    domForm.addEventListener('submit', function(event) { // recibe la info cuando se envia el form
      event.preventDefault(); // para que no se recargue la pagina

      //obtiene los input y trim le saca los espacios blancos al principio y al final
      const cedula = document.getElementById('cedula').value.trim();
      const password = document.getElementById('password').value.trim();

       // Campos obligatorios // || o
      if (!cedula || !password) {
        Swal.fire({ icon: 'error', title: 'Error', text: i18next.t('allFieldsRequired') });
        return;
      }

      // Cédula solo números
      if (isNaN(cedula)){ //true si no es un valor numerico
        Swal.fire({ icon: 'error', title: 'Error', text: i18next.t('idMustBeNumbers') });
        return;
      }

      // Cédula 8 dígitos
      if (cedula.length !== 8){
        Swal.fire({ icon: 'error', title: 'Error', text: i18next.t('idMustHave8Digits') });
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


        //fetch funcion para colicitudes HTTP
        fetch(this.action, { //obtiene atributo action
            method: "POST",
            body: formData //donde envia los datos
        })
        .then(res => res.json()) // pasa a respuesta json
        .then(data => { //objeto recibido
            console.log(data);
            if (data.success) {
                // Mensaje de éxito antes de redirigir
               Swal.fire({
                icon: 'success',
                title: i18next.t('welcome'),
                text: i18next.t('successfulLogin'),
                timer: 2000, // se cierra despues de dos segundos
                showConfirmButton: false //oculta el boton de confirmar
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
        text: i18next.t('serverError') 
    });
});
    });
  }

  const cerrarSesionBtns = document.querySelectorAll('.btn-cerrar-sesion'); //selecciona elementos con esa clase
  cerrarSesionBtns.forEach(btn => { //recorre cada boton encontrado
    btn.addEventListener('click', (e) => { //escucha el click
      e.preventDefault();
      Swal.fire({
        title: i18next.t('logoutConfirmTitle'),
        text: i18next.t('logoutConfirmText'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: i18next.t('logoutConfirmButton'),
        cancelButtonText: i18next.t('cancel')
      }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = './../../../utils/log-out.php'; //redirige al php encargado de cerrar sesion
        }
      });
    });
  });
});

