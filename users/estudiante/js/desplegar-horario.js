 // Selecciona todos los elementos que tienen la clase 'boton-opciones'
 document.querySelectorAll('.boton-opciones').forEach(btn => {
  // A cada botón se le agrega un evento que se ejecuta al hacer clic
  btn.addEventListener('click', () => {
    //accede a la lista de clases CSS del botón.
    //agrega la clase activa (para qu aparezca la tabla)
    btn.classList.toggle('active');
    //selecciona el elemento que viene después del botón en el HTML.
    const content = btn.nextElementSibling;
    //Si el display actual es 'block', entonces cámbialo a 'none' (ocúltalo).
    //De lo contrario, cámbialo a 'block' (muéstralo).
    content.style.display = content.style.display === 'block' ? 'none' : 'block';
  });
});
