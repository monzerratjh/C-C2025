const form = document.querySelector('form');
const ci_usuario = document.getElementById('ci_usuario');
const nombre_usuario = document.getElementById('nombre_usuario');
const apellido_usuario = document.getElementById('apellido_usuario');
const telefono_usuario = document.getElementById('telefono_usuario');
const gmail_usuario = document.getElementById('gmail_usuario');
const contrasenia_usuario = document.getElementById('contrasenia_usuario');


function validacionCampos() {
    if (ci_usuario.value.trim() == "" || 
        contrasenia_usuario.value.trim() == "" || 
        nombre_usuario.value.trim() == "" || 
        apellido_usuario.value.trim() == "" || 
        telefono_usuario.value.trim() == "" || 
        gmail_usuario.value.trim() == "") {
        alert("Por favor, complete todos los campos");
        return false;
    }
    return true;
}


function validacionLongitud(ci_usuario, telefono_usuario) {
    if (ci_usuario.value.trim().length !== 8 || isNaN(ci_usuario.value.trim())) {
        alert("La cédula debe tener 8 números válidos");
        return false;
    } else if(telefono_usuario.value.trim().length !== 9 || isNaN(telefono_usuario.value.trim())) {
        alert("El teléfono debe tener 9 números válidos");
        return false;
    }
    return true;
}


function validarConstrasenia (contrasenia_usuario){
    if (contrasenia_usuario.value.trim().length < 5) {
        alert("La contraseña debe tener al menos 5 caracteres.");
            return false; // Evitar el envío del formulario
        }
        if (!/[A-Z]/.test(contrasenia_usuario.value.trim()) || !/[0-9]/.test(contrasenia_usuario.value.trim())) {
        alert("La contraseña debe contener al menos una letra mayúscula y un número.");
            return false; // Evitar el envío del formulario
        }
    return true;
}


form.addEventListener('submit', function(e) {
    if( !validacionCampos() ||
        !validacionLongitud(ci_usuario, telefono_usuario) ||
        !validarConstrasenia(contrasenia_usuario)) {
        e.preventDefault();
    }
});


    // GRUPOS
const orientacionesValidas = [
    "Tecnologías de la Información",
    "Tecnologías de la Información Bilingüe",
    "Finest IT y Redes",
    "Redes y Comunicaciones Ópticas",
    "Diseño Gráfico en Comunicación Visual",
    "Secretariado Bilingüe - Inglés",
    "Tecnólogo en Ciberseguridad"
];

document.getElementById('formGrupo').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita que el formulario se envíe directamente

    const nombre = document.getElementById('nombre').value.trim();
    const orientacion = document.getElementById('orientacionInput').value.trim();
    const turno = document.getElementById('turno').value;
    const cantidad = parseInt(document.getElementById('cantidad').value);

    if (nombre === '') {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese el nombre del grupo' });
        return;
    } else if (nombre.length > 6){
        Swal.fire({ icon: 'error', title: 'Error', text: 'El nombre del grupo debe ser menor a 6 caracteres' });
        return;
    } 

    if (!orientacionesValidas.includes(orientacion)) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'La orientación no es válida' });
        return;
    }

    if (turno === '') {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Seleccione un turno' });
        return;
    }

    if (isNaN(cantidad) || cantidad < 1) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Cantidad de alumnos inválida' });
        return;
    }

    // Si pasó todas las validaciones, enviar el formulario
    this.submit();
});

// confirmación para eliminar con SweetAlert2
document.addEventListener('click', function(e){
  if(e.target.matches('.eliminar-grupo-btn')) {
    const id = e.target.dataset.id;
    Swal.fire({
      title: '¿Eliminar grupo?',
      text: "Esta acción no se puede deshacer.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.isConfirmed) {
        // crear y enviar un form POST para eliminar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'grupo-accion.php';
        form.innerHTML = '<input type="hidden" name="accion" value="eliminar">' +
                         '<input type="hidden" name="id_grupo" value="' + id + '">';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
});