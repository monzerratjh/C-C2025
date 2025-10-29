document.addEventListener('DOMContentLoaded', () => {

  // === Detectar página actual ===
  const esIndex = document.querySelector('#formPaso1');
  const esEditar = document.querySelector('#formAtributos');

  // ======================================================================
  // =========================== PÁGINA INDEX =============================
  // ======================================================================
  if (esIndex) {

    // --- Click sobre tarjetas ---
    document.addEventListener('click', e => {
      const card = e.target.closest('.espacio-card');
      const isButton = e.target.closest('button');
      if (isButton) return;
      if (card) {
        const id = card.dataset.id;
        window.location.href = `editar-propiedad-espacio.php?id_espacio=${id}`;
      }
    });

    // --- Variables principales ---
    const paso1 = document.getElementById('formPaso1');
    const paso2 = document.getElementById('formPaso2');
    const btnSiguiente = document.getElementById('btnSiguiente');
    const btnGuardarAtributos = document.getElementById('btnGuardarAtributos');
    const modalTitulo = document.getElementById('modalTitulo');
    const tipoDetectado = document.getElementById('tipo_espacio')?.value || '';

    let idEspacioCreado = null;
    let currentMode = 'create';
    let editingId = null;

    // --- Habilitar/deshabilitar inputs de cantidad ---
    document.querySelectorAll('.toggleCantidad').forEach(chk => {
      chk.addEventListener('change', () => {
        const target = document.getElementById(chk.dataset.target);
        target.disabled = !chk.checked;
        if (!chk.checked) target.value = '';
      });
    });

    // --- Validar formulario ---
    const validar = () => {
      const nombre = document.getElementById('nombre_espacio').value.trim();
      const cap = parseInt(document.getElementById('capacidad_espacio').value, 10);
      if (!nombre) return Swal.fire('Error', 'Ingrese nombre.', 'error'), false;
      if (isNaN(cap) || cap < 1 || cap > 100)
        return Swal.fire('Error', 'Capacidad debe ser entre 1 y 100.', 'error'), false;
      return true;
    };

    // --- Preparar nuevo espacio ---
    window.prepararNuevoEspacio = () => {
      currentMode = 'create';
      editingId = null;
      idEspacioCreado = null;
      paso1.reset();
      document.getElementById('accion').value = 'crear';
      document.getElementById('id_espacio').value = '';
      modalTitulo.textContent = 'Agregar espacio';
      paso1.classList.remove('d-none');
      paso2.classList.add('d-none');
      btnSiguiente.classList.remove('d-none');
      btnGuardarAtributos.classList.add('d-none');
      btnSiguiente.textContent = 'Siguiente';
    };

    // --- Cargar espacio existente (editar) ---
    window.cargarEditarEspacio = (espacio) => {
      currentMode = 'edit';
      editingId = espacio.id_espacio;
      paso1.classList.remove('d-none');
      paso2.classList.add('d-none');
      modalTitulo.textContent = 'Editar espacio';
      btnSiguiente.classList.remove('d-none');
      btnGuardarAtributos.classList.add('d-none');
      btnSiguiente.textContent = 'Guardar';

      document.getElementById('accion').value = 'editar';
      document.getElementById('id_espacio').value = espacio.id_espacio;
      document.getElementById('nombre_espacio').value = espacio.nombre_espacio;
      document.getElementById('capacidad_espacio').value = espacio.capacidad_espacio;
      document.getElementById('historial_espacio').value = espacio.historial_espacio;
    };

    // --- Guardar o crear espacio ---
    const handleBtnPrincipal = () => {
      if (!validar()) return;
      const fd = new FormData(paso1);

      // CREAR
      if (currentMode === 'create') {
        fd.set('accion', 'crear');
        fd.set('tipo_espacio', tipoDetectado);

        fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(d => {
            if (d.type === 'success') {
              idEspacioCreado = d.id_espacio;
              document.getElementById('id_espacio_attr').value = idEspacioCreado;
              paso1.classList.add('d-none');
              paso2.classList.remove('d-none');
              btnSiguiente.classList.add('d-none');
              btnGuardarAtributos.classList.remove('d-none');
              modalTitulo.textContent = 'Agregar atributos al espacio';
            } else Swal.fire('Error', d.message, 'error');
          })
          .catch(() => Swal.fire('Error', 'Hubo un problema con la conexión', 'error'));
      }
      // EDITAR
      else {
        fd.set('accion', 'editar');
        fd.set('id_espacio', editingId);

        fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(d => {
            Swal.fire(d.type === 'success' ? 'Actualizado' : 'Error', d.message, d.type)
              .then(() => { if (d.type === 'success') location.reload(); });
          })
          .catch(() => Swal.fire('Error', 'No se pudo actualizar el espacio', 'error'));
      }
    };

    btnSiguiente.addEventListener('click', handleBtnPrincipal);

    // --- Guardar atributos ---
    btnGuardarAtributos.addEventListener('click', () => {
      const fd = new FormData(paso2);
      fd.append('accion', 'atributos');
      fd.append('id_espacio', idEspacioCreado);

      fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
          Swal.fire(d.type === 'success' ? 'Éxito' : 'Error', d.message, d.type)
            .then(() => { if (d.type === 'success') location.reload(); });
        })
        .catch(() => Swal.fire('Error', 'No se pudieron guardar los atributos', 'error'));
    });

    // --- Eliminar ---
    document.addEventListener('click', e => {
      const btn = e.target.closest('.eliminar-espacio-boton');
      if (!btn) return;
      e.stopPropagation();
      const id = btn.dataset.id;

      Swal.fire({
        title: '¿Eliminar espacio?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(res => {
        if (!res.isConfirmed) return;
        const fd = new FormData();
        fd.append('accion', 'eliminar');
        fd.append('id_espacio', id);
        fetch('../espacio-accion.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(d => {
            Swal.fire(d.type === 'success' ? 'Eliminado' : 'Error', d.message, d.type)
              .then(() => { if (d.type === 'success') location.reload(); });
          })
          .catch(() => Swal.fire('Error', 'No se pudo eliminar el espacio', 'error'));
      });
    });
  }

  // ======================================================================
  // ====================== PÁGINA EDITAR-PROPIEDAD =======================
  // ======================================================================
  if (esEditar) {
    document.querySelectorAll('.toggleCantidad').forEach(chk => {
      chk.addEventListener('change', () => {
        const t = document.getElementById(chk.dataset.target);
        t.disabled = !chk.checked;
        if (!chk.checked) t.value = '';
      });
    });

    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarAtributos'));
    document.getElementById('btnEditar')?.addEventListener('click', () => modalEditar.show());

    document.getElementById('guardarAtributosBtn')?.addEventListener('click', () => {
      const fd = new FormData(document.getElementById('formAtributos'));
      fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
          Swal.fire(d.type === 'success' ? 'Guardado' : 'Error', d.message, d.type)
            .then(() => { if (d.type === 'success') location.reload(); });
        })
        .catch(() => Swal.fire('Error', 'No se pudo guardar los atributos', 'error'));
    });

    document.getElementById('btnEliminar')?.addEventListener('click', () => {
      Swal.fire({
        title: '¿Eliminar espacio?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(res => {
        if (!res.isConfirmed) return;
        const fd = new FormData();
        fd.append('accion', 'eliminar');
        fd.append('id_espacio', document.querySelector('[name="id_espacio"]').value);
        fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(d => {
            Swal.fire(d.type === 'success' ? 'Eliminado' : 'Error', d.message, d.type)
              .then(() => { if (d.type === 'success') window.location.href = 'adscripto-espacio.php'; });
          })
          .catch(() => Swal.fire('Error', 'No se pudo eliminar el espacio', 'error'));
      });
    });
  }
});