document.addEventListener("DOMContentLoaded", () => {
  // Inicialmente ocultamos todos los contenidos
  document.querySelectorAll(".boton-opciones").forEach(btn => {
    const content = btn.nextElementSibling;
    if (content) {
      content.style.display = "none"; // todo cerrado al cargar
    }

    // Al hacer clic, alterna la visibilidad del contenido
    btn.addEventListener("click", () => {
      const isVisible = content.style.display === "block";
      // Cierra todos los demás acordeones para mantener limpio
      document.querySelectorAll(".boton-opciones").forEach(b => {
        const otherContent = b.nextElementSibling;
        if (otherContent) otherContent.style.display = "none";
        b.classList.remove("active");
      });
      // Si estaba cerrado, ahora lo abre
      if (!isVisible) {
        content.style.display = "block";
        btn.classList.add("active");
      }
    });
  });

  // Botón para abrir el modal de nueva reserva
  document.getElementById("hacerReservaBtn").addEventListener("click", function() {
    const modal = new bootstrap.Modal(document.getElementById("modalReserva"));
    modal.show();
  });
});
