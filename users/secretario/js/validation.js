document.addEventListener('DOMContentLoaded', () => {

// Mostrar alertas según parámetros GET (error o mensaje)

//obtener y manejar los parámetros que vienen en la URL
  const params = new URLSearchParams(window.location.search);

  if (window.Swal && params.has("error")) {
    const error = params.get("error");
    const campo = params.get("campo") || "dato";

   // objeto errores
    const errores = {
      CamposVacios: {
        title: i18next.t('emptyFields') ,
        text: i18next.t('pleaseFillIn') ,
      },
      CiInvalida: {
        title: i18next.t('invalidID'),
        text: i18next.t('IDMustContain8Digits'),
      },
      CedulaInvalidaDigito: {
        title: i18next.t('invalidID'),
        text: i18next.t('verificationDigit'),
      },
      TelefonoInvalido: {
        title: i18next.t('invalidPhoneNumber'),
        text: i18next.t('numberConatins9Digits'),
      },
      ContraseniaInvalida: {
        title: i18next.t('invalidpassword'),
        text: i18next.t('passwordCharacters'),
      },
      Duplicado: {
        title: i18next.t('duplicateUser'),
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
      InsercionExitosa: i18next.t('userCreatedSuccessfully') ,
      EdicionExitosa: i18next.t('editSuccessfully') ,
      EliminacionExitosa:  i18next.t('deletionSuccessfully') ,
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
  

  // Validaciones de formularios (crear y editar)

  // EDICIÓN 
  document.addEventListener('submit', (e) => {
    const form = e.target;

    // Si el formulario NO tiene un id que empiece con "editarUsuarioForm", se sale y no hace nada.
  // Esto evita que se ejecute el código con otros formularios.
    if (!form.matches('form[id^="editarUsuarioForm"]')) return; 

    e.preventDefault();
    const ok = validarFormularioEdicion(form);
    if (!ok) return;

    Swal.fire({
      title:  i18next.t('areYouSure') ,
      text: i18next.t('wantSaveChanges') ,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: i18next.t('yesSave') ,
      cancelButtonText: i18next.t('Cancel') 
    }).then((r) => { if (r.isConfirmed) form.submit(); });
  });

  // CREACIÓN 
  // Busca en el documento el formulario cuyo atributo action sea "./agregar-usuario.php"
  const formCreacion = document.querySelector('form[action="./agregar-usuario.php"]');
  if (formCreacion) {
    formCreacion.addEventListener('submit', (e) => {
      e.preventDefault();
      const ok = validarFormulario(formCreacion, true);
      if (!ok) return;

      Swal.fire({
        title: i18next.t('createUser'),
        text: i18next.t('addNewUser'),
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: i18next.t('yesCreate') ,
        cancelButtonText: i18next.t('Cancel') 
      }).then((r) => { if (r.isConfirmed) formCreacion.submit(); });
    });
  }

  
  // Confirmación antes de eliminar usuario

  document.querySelectorAll('a.eliminar').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      // Obtiene la dirección (href) del enlace, que apunta al script de eliminación
      const url = btn.getAttribute('href');

      Swal.fire({
        title: i18next.t('deleteUser'),
        text:  i18next.t('actionNotBeUndone') ,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText:  i18next.t('yesDelete') ,
        cancelButtonText:  i18next.t('cancel') 
      }).then((r) => {
        if (r.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });

});


// Funciones auxiliares

function leer(form, name) {
  const el = form.querySelector(`[name="${name}"]`); //Busca dentro del formulario (form) un elemento que tenga el atributo name igual al valor pasado en la variable name.
  // Devuelve el valor del campo:
  // - Si el campo existe, toma su valor
  // - Si no existe, devuelve una cadena vacía
  // - Convierte a texto y elimina espacios sobrantes
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
    alertSwal(i18next.t('emptyFields'),i18next.t('pleaseFillIn'));
    return false;
  }

  if (!/^\d{8}$/.test(ci)) {
    alertSwal(i18next.t('invalidID'), i18next.t('IDMustContain8Digits'));
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(nombre)) {
    alertSwal(i18next.t('invalidName'), i18next.t('containName'));
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(apellido)) {
    alertSwal(i18next.t('invalidLastName'), i18next.t('lastNameContain'));
    return false;
  }

  if (!/^\d{9}$/.test(telefono)) {
    alertSwal(i18next.t('invalidPhoneNumber'), i18next.t('numberContains9Digits'));
    return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    alertSwal(i18next.t('invalidEmail'), i18next.t('pleaseValidEmail'));
    return false;
  }

  if (!contrasenia) {
    alertSwal(i18next.t('passwordRequired'), i18next.t('enterPassword'));
    return false;
  }

  if (contrasenia.length < 8 || contrasenia.length > 20) {
    alertSwal(i18next.t('invalidpassword'), i18next.t('requiere2'));
    return false;
  }

  const tieneMayus = /[A-Z]/.test(contrasenia);
  const tieneMinus = /[a-z]/.test(contrasenia);
  const tieneNumero = /[0-9]/.test(contrasenia);
  if (!tieneMayus || !tieneMinus || !tieneNumero) {
    alertSwal(i18next.t('invalidpassword'),i18next.t('requiere1'));
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
    alertSwal(i18next.t('emptyFields'), i18next.t('allFieldsRequired'));
    return false;
  }

  if (!/^\d{8}$/.test(ci)) {
    alertSwal(i18next.t('invalidID'), i18next.t('IDMustContain8Digits'));
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(nombre)) {
    alertSwal(i18next.t('invalidName'),  i18next.t('containName'));
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(apellido)) {
    alertSwal(i18next.t('invalidLastName'), i18next.t('lastNameContain'));
    return false;
  }

  if (!/^\d{9}$/.test(telefono)) {
    alertSwal(i18next.t('invalidPhoneNumber'), i18next.t('numberContains9Digits'));
    return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gmail)) {
    alertSwal(i18next.t('invalidEmail'), i18next.t('plaseValidEmail'));
    return false;
  }

  if (contrasenia) {
    if (contrasenia.length < 8 || contrasenia.length > 20) {
      alertSwal(i18next.t('invalidpassword'), i18next.t('require2'));
      return false;
    }

    const tieneMayus = /[A-Z]/.test(contrasenia);
    const tieneMinus = /[a-z]/.test(contrasenia);
    const tieneNumero = /[0-9]/.test(contrasenia);

    if (!tieneMayus || !tieneMinus || !tieneNumero) {
      alertSwal(i18next.t('invalidpassword'),  i18next.t('require1'));
      return false;
    }
  }

  return true;
}


function alertSwal(title, text) { //si sweets alerts esta disponible sino error.
  if (window.Swal) {
    Swal.fire({ icon: 'error', title, text, confirmButtonColor: '#d33' });
  } else {
    alert(`${title}\n${text}`);
  }
}


// Limpieza del URL solo después de alertas exitosas
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
