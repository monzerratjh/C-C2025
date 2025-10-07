 document.querySelectorAll('.boton-opciones').forEach(btn => {
  btn.addEventListener('click', () => {
    btn.classList.toggle('active');
    const content = btn.nextElementSibling;
    content.style.display = content.style.display === 'block' ? 'none' : 'block';
  });
});


document.getElementById('hacerReservaBtn').addEventListener('click', function() {
  var modal = new bootstrap.Modal(document.getElementById('modalReserva'));
  modal.show();
});
