document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formReserva'); // ID del formulario en el modal
  const accionInput = document.getElementById('accion');
  const idInput = document.getElementById('id_reserva');

  // Prepara el modal para nueva reserva
  window.prepararNuevaReserva = function() {
    accionInput.value = 'insertar';
    idInput.value = '';
  };

  // Enviar formulario de reserva
  form.addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(form);

    fetch('reserva-accion.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.type === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: data.message,
            timer: 1500,
            showConfirmButton: false
          }).then(() => location.reload());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message });
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al procesar la solicitud.' });
      });
  });

  // Botones para cancelar reservas
  document.querySelectorAll('.cancelar-reserva-boton').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      Swal.fire({
        title: '¿Cancelar reserva?',
        text: 'Esta acción liberará el espacio.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No'
      }).then(result => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('accion', 'cancelar');
          formData.append('id_reserva', id);

          fetch('reserva-accion.php', {
            method: 'POST',
            body: formData
          })
            .then(res => res.json())
            .then(data => {
              if (data.type === 'success') {
                Swal.fire({
                  icon: 'success',
                  title: 'Cancelada',
                  text: data.message,
                  timer: 1500,
                  showConfirmButton: false
                }).then(() => location.reload());
              } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
              }
            })
            .catch(err => {
              console.error(err);
              Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al procesar la solicitud.' });
            });
        }
      });
    });
  });
});
