// Obtener el botón que abre el modal
document.getElementById('hacerReservaBtn').addEventListener('click', function() {
  var modal = new bootstrap.Modal(document.getElementById('modalReserva'));
  modal.show();
});
