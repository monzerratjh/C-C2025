 document.querySelectorAll('.boton-opciones').forEach(btn => {
  btn.addEventListener('click', () => {
    btn.classList.toggle('active');
    // Obtiene el siguiente elemento hermano del botón (el contenido asociado)
    const content = btn.nextElementSibling;
    // Si el contenido está visible (display: block), lo oculta; si está oculto, lo muestra
   
    content.style.display = content.style.display === 'block' ? 'none' : 'block';
  });
});
