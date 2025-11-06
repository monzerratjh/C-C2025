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
      // Fallback si SweetAlert no cargó
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
  // si no existe, devuelve string vacío; si existe, devuelve value recortado
  return (el?.value ?? '').toString().trim();
}

function validarFormulario(form, esCreacion = true) {
  const ci = leer(form,'ci_usuario');
  const nombre = leer(form,'nombre_usuario');
  const apellido = leer(form,'apellido_usuario');
  const gmail = leer(form,'gmail_usuario');
  const telefono = leer(form,'telefono_usuario');
  const cargo = leer(form,'cargo_usuario');
  const contrasenia = leer(form,'contrasenia_usuario');

  console.log('Valores creación:', {ci, nombre, apellido, gmail, telefono, cargo, contraseniaVacia: !contrasenia});

  if (!ci || !nombre || !apellido || !gmail || !telefono || !cargo) {
    alertSwal('Campos incompletos','Todos los campos son obligatorios'); return false;
  }

  if (!/^\d{8}$/.test(ci)) {
    alertSwal('Cédula inválida','La cédula debe tener 8 dígitos'); return false;
  }

  if (!/^\d{9}$/.test(telefono)) {
    alertSwal('Teléfono inválido','El teléfono debe tener 9 dígitos'); return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    alertSwal('Email inválido','Por favor ingrese un email válido'); return false;
  }

  if (!contrasenia) {
    alertSwal('Contraseña requerida','Debe ingresar una contraseña'); return false;
  }

  if (contrasenia.length < 8 || contrasenia.length > 20) {
    alertSwal('Contraseña inválida','La contraseña debe tener entre 8 y 20 caracteres'); return false;
  }

  const tieneMayus = /[A-Z]/.test(contrasenia);
  const tieneMinus = /[a-z]/.test(contrasenia);
  const tieneNumero = /[0-9]/.test(contrasenia);
  if (!tieneMayus || !tieneMinus || !tieneNumero) {
    alertSwal('Contraseña inválida','La contraseña debe tener: al menos una MAYÚSCULA, una minúscula y un número'); return false;
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
  // en edición, la contraseña puede venir vacía (no cambiar)
  const contrasenia = leer(form,'contrasenia_usuario');

  console.log('Valores edición:', {ci, nombre, apellido, gmail, telefono, cargo, contrasenia});

  if (!ci || !nombre || !apellido || !gmail || !telefono || !cargo) {
    alertSwal('Campos incompletos','Todos los campos son obligatorios'); return false;
  }

  if (!/^\d{8}$/.test(ci)) {
    alertSwal('Cédula inválida','La cédula debe tener 8 dígitos'); return false;
  }

  if (!/^\d{9}$/.test(telefono)) {
    alertSwal('Teléfono inválido','El teléfono debe tener 9 dígitos'); return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    alertSwal('Email inválido','Por favor ingrese un email válido'); return false;
  }

  if (contrasenia) {
    if (contrasenia.length < 8 || contrasenia.length > 20) {
      alertSwal('Contraseña inválida','La contraseña debe tener entre 8 y 20 caracteres'); return false;
    }
    const tieneMayus = /[A-Z]/.test(contrasenia);
    const tieneMinus = /[a-z]/.test(contrasenia);
    const tieneNumero = /[0-9]/.test(contrasenia);
    if (!tieneMayus || !tieneMinus || !tieneNumero) {
      alertSwal('Contraseña inválida','La contraseña debe tener: al menos una MAYÚSCULA, una minúscula y un número'); return false;
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
