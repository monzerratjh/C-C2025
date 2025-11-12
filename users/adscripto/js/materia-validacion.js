document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  // --- Manejo de errores ---
  if (window.Swal && params.has("error")) {
    const error = params.get("error");

    const errores = {
      CampoVacio: {
        title: i18next.t('emptyFields'),
        text: i18next.t('pleaseFillIn')
      },
      NombreInvalido: {
        title: i18next.t('invalidName'),
        text: i18next.t('containName')
      },
      MateriaDuplicada: {
        title: i18next.t('duplicateSubject'),
        text: i18next.t('enterAnotherSubject') || 'Por favor ingrese otra materia.'
      },
      InsercionFallida: {
        title: i18next.t('insertFailed') || 'Inserción Fallida',
        text: i18next.t('tryAgain')
      },
      ActualizacionFallida: {
        title: i18next.t('updateFailed') || 'Falla en la actualización',
        text: i18next.t('tryAgain')
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
      EdicionExitosa: i18next.t('succesfulEdit'),
      InsercionExitosa: i18next.t('successfulAssignment'),
      EliminacionExitosa: i18next.t('successfulDeletion'),
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
        title: i18next.t('areYouSure'),
        text: i18next.t('actionNotBeUndone'),
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: i18next.t('yesDelete'),
        cancelButtonText: i18next.t('cancel')
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });

});
