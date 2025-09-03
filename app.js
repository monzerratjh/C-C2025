document.addEventListener("DOMContentLoaded", () => {
  const botones = document.querySelectorAll(".boton-dia");

  botones.forEach(boton => {
    boton.addEventListener("click", () => {
      const contenido = boton.nextElementSibling; // div.contenido-dia

      // Si ya está visible, lo oculta; si está oculto, lo muestra
      if (contenido.style.display === "block") {
        contenido.style.display = "none";
      } else {
        // Opcional: cerrar todos los demás antes de abrir este
        document.querySelectorAll(".contenido-dia").forEach(div => {
          div.style.display = "none";
        });

        contenido.style.display = "block";
      }
    });
  });
});
