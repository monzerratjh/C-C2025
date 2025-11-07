document.addEventListener('DOMContentLoaded', () => {

  // ------------------------------------------------------
  // Mostrar alertas según parámetros GET (error o mensaje)
  // ------------------------------------------------------
  const params = new URLSearchParams(window.location.search);

  if (window.Swal && params.has("error")) {
    const error = params.get("error");
    const campo = params.get("campo") || "dato";

   // objeto errores
    const errores = {
      CamposVacios: {
        title: "Campos vacíos",
        text: "Por favor complete todos los campos.",
      },
      CiInvalida: {
        title: "Cédula inválida",
        text: "Debe tener 8 dígitos.",
      },
      CedulaInvalidaDigito: {
        title: "Cédula inválida",
        text: "El dígito verificador de la cédula no es válido.",
      },
      TelefonoInvalido: {
        title: "Teléfono inválido",
        text: "Debe tener 9 dígitos.",
      },
      ContraseniaInvalida: {
        title: "Contraseña inválida",
        text: "Debe tener entre 8 a 20 caracteres, incluir mayúsculas, minúsculas y números.",
      },
      Duplicado: {
        title: "Usuario duplicado",
        text: `Ya existe un usuario con ese ${campo} registrado.`,
      },
    };

    const alerta = errores[error];
    if (alerta)
      Swal.fire({
        icon: "error",
        title: alerta.title,
        text: alerta.text,
        confirmButtonColor: "#d33",
      });
  }

  // Reabrir el modal automáticamente si corresponde
  if (params.get("abrirModal") === "true") {
    const idUsuario = params.get("id_usuario");
    let modalEl;

    if (idUsuario) {
      // Si hay un id_usuario => abrir el modal de edición específico
      modalEl = document.getElementById(`update_modal${idUsuario}`);
    } else {
      // Si no hay id_usuario => abrir el modal de creación
      modalEl = document.getElementById("modalUsuario");
    }

    if (modalEl) {
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
    }
  }

  // Mensajes de éxito
  if (window.Swal && params.has("msg")) {
    const msg = params.get("msg");
    const exitos = {
      InsercionExitosa: "Creación de Usuario Exitosa",
      EdicionExitosa: "¡Edición Exitosa!",
      EliminacionExitosa: "¡Eliminación Exitosa!",
    };
    if (exitos[msg])
      Swal.fire({
        icon: "success",
        title: exitos[msg],
        confirmButtonColor: "rgba(95, 102, 207, 1)",
      }).then(() => {
    // Recarga la página limpia después de cerrar el SweetAlert
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.pathname);
    }
    location.reload();
  });
}
  

  // ------------------------------------------------------
  // Validaciones de formularios (crear y editar)
  // ------------------------------------------------------

  // ---- EDICIÓN ----
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form.matches('form[id^="editarUsuarioForm"]')) return;

    e.preventDefault();
    const ok = validarFormularioEdicion(form);
    if (!ok) return;

    Swal.fire({
      title: '¿Estás seguro?',
      text: '¿Deseas guardar los cambios?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, guardar',
      cancelButtonText: 'Cancelar'
    }).then((r) => { if (r.isConfirmed) form.submit(); });
  });

  // ---- CREACIÓN ----
  const formCreacion = document.querySelector('form[action="./agregar-usuario.php"]');
  if (formCreacion) {
    formCreacion.addEventListener('submit', (e) => {
      e.preventDefault();
      const ok = validarFormulario(formCreacion, true);
      if (!ok) return;

      Swal.fire({
        title: '¿Crear usuario?',
        text: 'Se agregará un nuevo usuario',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, crear',
        cancelButtonText: 'Cancelar'
      }).then((r) => { if (r.isConfirmed) formCreacion.submit(); });
    });
  }

  // ------------------------------------------------------
  // Confirmación antes de eliminar usuario
  // ------------------------------------------------------
  document.querySelectorAll('a.eliminar').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const url = btn.getAttribute('href');

      Swal.fire({
        title: '¿Eliminar usuario?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((r) => {
        if (r.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });

});

// ------------------------------------------------------
// Funciones auxiliares
// ------------------------------------------------------
function leer(form, name) {
  const el = form.querySelector(`[name="${name}"]`);
  return (el?.value ?? '').toString().trim();
}

function validarFormulario(form, esCreacion = true) {
  const ci = leer(form, 'ci_usuario');
  const nombre = leer(form, 'nombre_usuario');
  const apellido = leer(form, 'apellido_usuario');
  const gmail = leer(form, 'gmail_usuario');
  const telefono = leer(form, 'telefono_usuario');
  const cargo = leer(form, 'cargo_usuario');
  const contrasenia = leer(form, 'contrasenia_usuario');

  if (!ci || !nombre || !apellido || !gmail || !telefono || !cargo) {
    alertSwal('Campos incompletos', 'Todos los campos son obligatorios');
    return false;
  }

  if (!/^\d{8}$/.test(ci)) {
    alertSwal('Cédula inválida', 'La cédula debe tener 8 dígitos');
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(nombre)) {
    alertSwal('Nombre inválido', 'El nombre debe contener solo letras y tener entre 3 y 30 caracteres');
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(apellido)) {
    alertSwal('Apellido inválido', 'El apellido debe contener solo letras y tener entre 3 y 30 caracteres');
    return false;
  }

  if (!/^\d{9}$/.test(telefono)) {
    alertSwal('Teléfono inválido', 'El teléfono debe tener 9 dígitos');
    return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    alertSwal('Email inválido', 'Por favor ingrese un email válido');
    return false;
  }

  if (!contrasenia) {
    alertSwal('Contraseña requerida', 'Debe ingresar una contraseña');
    return false;
  }

  if (contrasenia.length < 8 || contrasenia.length > 20) {
    alertSwal('Contraseña inválida', 'Debe tener entre 8 y 20 caracteres');
    return false;
  }

  const tieneMayus = /[A-Z]/.test(contrasenia);
  const tieneMinus = /[a-z]/.test(contrasenia);
  const tieneNumero = /[0-9]/.test(contrasenia);
  if (!tieneMayus || !tieneMinus || !tieneNumero) {
    alertSwal('Contraseña inválida', 'Debe contener al menos una MAYÚSCULA, una minúscula y un número');
    return false;
  }

  return true;
}

function validarFormularioEdicion(form) {
  const ci = leer(form, 'ci_usuario');
  const nombre = leer(form, 'nombre_usuario');
  const apellido = leer(form, 'apellido_usuario');
  const gmail = leer(form, 'gmail_usuario');
  const telefono = leer(form, 'telefono_usuario');
  const cargo = leer(form, 'cargo_usuario');
  const contrasenia = leer(form, 'contrasenia_usuario');

  if (!ci || !nombre || !apellido || !gmail || !telefono || !cargo) {
    alertSwal('Campos incompletos', 'Todos los campos son obligatorios');
    return false;
  }

  if (!/^\d{8}$/.test(ci)) {
    alertSwal('Cédula inválida', 'La cédula debe tener 8 dígitos');
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(nombre)) {
    alertSwal('Nombre inválido', 'El nombre debe contener solo letras y tener entre 3 y 30 caracteres');
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(apellido)) {
    alertSwal('Apellido inválido', 'El apellido debe contener solo letras y tener entre 3 y 30 caracteres');
    return false;
  }

  if (!/^\d{9}$/.test(telefono)) {
    alertSwal('Teléfono inválido', 'El teléfono debe tener 9 dígitos');
    return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    alertSwal('Email inválido', 'Por favor ingrese un email válido');
    return false;
  }

  if (contrasenia) {
    if (contrasenia.length < 8 || contrasenia.length > 20) {
      alertSwal('Contraseña inválida', 'Debe tener entre 8 y 20 caracteres');
      return false;
    }

    const tieneMayus = /[A-Z]/.test(contrasenia);
    const tieneMinus = /[a-z]/.test(contrasenia);
    const tieneNumero = /[0-9]/.test(contrasenia);

    if (!tieneMayus || !tieneMinus || !tieneNumero) {
      alertSwal('Contraseña inválida', 'Debe contener al menos una MAYÚSCULA, una minúscula y un número');
      return false;
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

// ------------------------------------------------------
// Limpieza del URL solo después de alertas exitosas
// ------------------------------------------------------
const params = new URLSearchParams(window.location.search);
const msg = params.get("msg");

if (window.history.replaceState && msg) {
  // Solo limpia si hay uno de esots mensajes incluidos en msg
  if (["InsercionExitosa", "EdicionExitosa", "EliminacionExitosa"].includes(msg)) {
    setTimeout(() => {
      window.history.replaceState(null, null, window.location.pathname);
    }, 3000); // le da tiempo a SweetAlert a mostrarse
  }
}
