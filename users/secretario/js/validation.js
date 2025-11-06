//VALIDACIONES DEL LADO DEL CLIENTE

document.addEventListener('DOMContentLoaded', () => {
  // ---- EDICIÓN (delegación por seguridad) ----
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form.matches('form[id^="editarUsuarioForm"]')) return;

    e.preventDefault();

    const ok = validarFormularioEdicion(form);
    if (!ok) return;

    if (window.Swal) {
      Swal.fire({
        title: i18next.t('areYouSure'),
        text: '¿Deseas guardar los cambios?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText:  i18next.t('cancel')
      }).then((r) => { if (r.isConfirmed) form.submit(); });
    } else {
      if (confirm('¿Deseas guardar los cambios?')) form.submit();
    }
  });

  // ---- CREACIÓN ----
  const formCreacion = document.querySelector('form[action="./agregar-usuario.php"]');
  if (formCreacion) {
    formCreacion.addEventListener('submit', (e) => {
      e.preventDefault();
      const ok = validarFormulario(formCreacion, true);
      if (!ok) return;

      if (window.Swal) {
        Swal.fire({
          title: '¿Crear usuario?',
          text: 'Se agregará un nuevo usuario',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, crear',
          cancelButtonText:  i18next.t('cancel') 
        }).then((r) => { if (r.isConfirmed) formCreacion.submit(); });
      } else {
        if (confirm('¿Crear usuario?')) formCreacion.submit();
      }
    });
  }
});

function leer(form, name) {
  const el = form.querySelector(`[name="${name}"]`);
  return (el?.value ?? '').toString().trim();
}

// Expresión regular para nombres y apellidos
const regexNombreApellido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,15}$/;

function validarFormulario(form) {
  const ci = leer(form,'ci_usuario');
  const nombre = leer(form,'nombre_usuario');
  const apellido = leer(form,'apellido_usuario');
  const gmail = leer(form,'gmail_usuario');
  const telefono = leer(form,'telefono_usuario');
  const cargo = leer(form,'cargo_usuario');
  const contrasenia = leer(form,'contrasenia_usuario');

  if (!ci || !nombre || !apellido || !gmail || !telefono || !cargo || !contrasenia) {
    return alertSwal('Campos incompletos','Todos los campos son obligatorios');
  }

  if (!regexNombreApellido.test(nombre)) {
    return alertSwal('Nombre inválido','El nombre debe tener entre 3 y 15 letras (sin números)');
  }

  if (!regexNombreApellido.test(apellido)) {
    return alertSwal('Apellido inválido','El apellido debe tener entre 3 y 15 letras (sin números)');
  }

  if (!/^\d{8}$/.test(ci)) {
    return alertSwal('Cédula inválida','La cédula debe tener 8 dígitos');
  }

  if (!/^\d{9}$/.test(telefono)) {
    return alertSwal('Teléfono inválido','El teléfono debe tener 9 dígitos');
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    return alertSwal('Email inválido','Por favor ingrese un email válido');
  }

  // Contraseña
  if (contrasenia.length < 8 || contrasenia.length > 20) {
    return alertSwal('Contraseña inválida','Debe tener entre 8 y 20 caracteres');
  }
  if (!/[A-Z]/.test(contrasenia) || !/[a-z]/.test(contrasenia) || !/[0-9]/.test(contrasenia)) {
    return alertSwal('Contraseña inválida','Debe contener al menos: 1 mayúscula, 1 minúscula y 1 número');
  }

  return true;
}

function validarFormularioEdicion(form) {
  const ci = leer(form,'ci_usuario');
  const nombre = leer(form,'nombre_usuario');
  const apellido = leer(form,'apellido_usuario');
  const gmail = leer(form,'gmail_usuario');
  const telefono = leer(form,'telefono_usuario');
  const cargo = leer(form,'cargo_usuario');
  const contrasenia = leer(form,'contrasenia_usuario');

  if (!ci || !nombre || !apellido || !gmail || !telefono || !cargo) {
    return alertSwal('Campos incompletos','Todos los campos son obligatorios');
  }

  if (!regexNombreApellido.test(nombre)) {
    return alertSwal('Nombre inválido','El nombre debe tener entre 3 y 15 letras (sin números)');
  }

  if (!regexNombreApellido.test(apellido)) {
    return alertSwal('Apellido inválido','El apellido debe tener entre 3 y 15 letras (sin números)');
  }

  if (!/^\d{8}$/.test(ci)) {
    return alertSwal('Cédula inválida','La cédula debe tener 8 dígitos');
  }

  if (!/^\d{9}$/.test(telefono)) {
    return alertSwal('Teléfono inválido','El teléfono debe tener 9 dígitos');
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    return alertSwal('Email inválido','Por favor ingrese un email válido');
  }

  if (contrasenia) {
    if (contrasenia.length < 8 || contrasenia.length > 20) {
      return alertSwal('Contraseña inválida','Debe tener entre 8 y 20 caracteres');
    }
    if (!/[A-Z]/.test(contrasenia) || !/[a-z]/.test(contrasenia) || !/[0-9]/.test(contrasenia)) {
      return alertSwal('Contraseña inválida','Debe contener al menos: 1 mayúscula, 1 minúscula y 1 número');
    }
  }

  return true;
}

function alertSwal(title, text) {
  if (window.Swal) {
    Swal.fire({ icon: 'error', title, text, confirmButtonColor: '#d33' });
  } else {
    alert(`${title}\n${text}`);
  }
}