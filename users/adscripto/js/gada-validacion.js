document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  // === ERRORES ===
  if (params.has("error")) {
    const error = params.get("error");

    const errores = {
      AsignacionDuplicada: {
        title: i18next.t('assignmentExist'),
        text: i18next.t('enterANewOne')
      },
      FalloInsercion: {
        title:i18next.t('errorInserting'),
        text: i18next.t('tryAgain')
      }
    };

    if (errores[error]) {
      Swal.fire({
        icon: "error",
        title: errores[error].title,
        text: errores[error].text,
        confirmButtonColor: "#d33"
      });
    }
  }

  // === ÉXITOS ===
  if (params.has("msg")) {
    const msg = params.get("msg");

    const exitos = {
      InsercionExitosa: i18next.t('successfulAssignment'),
      EliminacionExitosa: i18next.t('successfulDeletion'),
      EdicionExitosa: i18next.t('succesfulEdit')
    };

    if (exitos[msg]) {
      Swal.fire({
        icon: "success",
        title: exitos[msg],
        confirmButtonColor: "rgba(85, 93, 218, 1)"
      }).then(() => {
        // Limpia la URL para evitar alertas al recargar
        if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.pathname);
        }
      });
    }
  }

  // === CONFIRMACIÓN DE ELIMINACIÓN ===
  document.querySelectorAll('a[href*="eliminar-gada.php"]').forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const url = btn.getAttribute("href");

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
