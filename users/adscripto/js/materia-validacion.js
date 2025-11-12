document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  // --- Manejo de errores ---
  if (window.Swal && params.has("error")) {
    const error = params.get("error");

    const errores = {
      CampoVacio: {
        title: i18next.t('emptyFields') || 'El campo no puede estar vacío',
        text: i18next.t('pleaseFillIn') || 'Por favor intente de nuevo'
      },
      NombreInvalido: {
        title: i18next.t('invalidName') || 'El nombre es inválido',
        text: i18next.t('containName') || 'Por favor intenténtelo con otro nombre.'
      },
      MateriaDuplicada: {
        title: i18next.t('duplicateSubject') || 'La materia ya existe',
        text: i18next.t('enterAnotherSubject') || 'Por favor ingrese otra materia.'
      },
      InsercionFallida: {
        title: i18next.t('insertFailed') || 'Inserción Fallida',
        text: i18next.t('tryAgain') || 'Por favor intente otra vez.'
      },
      ActualizacionFallida: {
        title: i18next.t('updateFailed') || 'Falla en la actualización',
        text: i18next.t('tryAgain') || 'Por favor intente otra vez.'
      }
    };

    const alerta = errores[error];
    if (alerta) {
      Swal.fire({
        icon: "error",
        title: alerta.title,
        text: alerta.text,
        confirmButtonColor: "#d33"
      });
    }
  }

  // --- Mensajes de éxito ---
  if (window.Swal && params.has("msg")) {
    const msg = params.get("msg");

    const exitos = {
      EdicionExitosa: i18next.t('editSuccessfully') || '¡Edición Exitosa!',
      InsercionExitosa: i18next.t('insertSuccessfully') || '¡Inserción Exitosa!',
      EliminacionExitosa: i18next.t('deletionSuccessfully') || '¡Eliminación Exitosa!',
    };

    if (exitos[msg]) {
      Swal.fire({
        icon: "success",
        title: exitos[msg],
        confirmButtonColor: "rgba(95, 102, 207, 1)"
      }).then(() => {
        if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.pathname);
        }
        location.reload();
      });
    }
  }

  // --- Confirmación antes de eliminar materia ---
  document.querySelectorAll('a[href*="delete_materia.php"]').forEach(boton => {
    boton.addEventListener("click", function(e) {
      e.preventDefault();
      const url = this.getAttribute("href");

      Swal.fire({
        title: i18next.t('areYouSure') || '¿Está seguro?',
        text: i18next.t('noRevertAction') || "No podrá deshacer esta acción.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: i18next.t('yesDelete') || 'Sí, eliminar',
        cancelButtonText: i18next.t('cancel') || 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });

});
