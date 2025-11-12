document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  // === ERRORES ===
  if (params.has("error")) {
    const error = params.get("error");

    const errores = {
      AsignacionDuplicada: {
        title: "La asignación ya existe",
        text: "Por favor ingrese una nueva"
      },
      FalloInsercion: {
        title: "Error al insertar",
        text: "Por favor intente otra vez"
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
      InsercionExitosa: "¡Asignación exitosa!",
      EliminacionExitosa: "¡Eliminación exitosa!",
      EdicionExitosa: "¡Edición exitosa!"
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
        title: "¿Está seguro?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
});
