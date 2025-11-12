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
      const card = e.target.closest('.espacio-cuerpo');
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

    // --- Habilitar/deshabilitar inputs de cantidad + "Otro (descripción)"
    document.querySelectorAll('.toggleCantidad').forEach(chk => {
      chk.addEventListener('change', () => {
        const targetId = chk.dataset.target;
        const target = document.getElementById(targetId);

        // Habilita/deshabilita el input de cantidad
        target.disabled = !chk.checked;
        if (!chk.checked) target.value = '';

        // Si es el checkbox de "Otro", también controla la descripción
        if (targetId === 'otro_personalizado') {
          const desc = document.getElementById('otro_descripcion');
          desc.disabled = !chk.checked;
          if (!chk.checked) desc.value = '';
        }
      });
    });

    // Alinear estado inicial de "Otro" al cargar
    const chkOtro = document.querySelector('.toggleCantidad[data-target="otro_personalizado"]');
    if (chkOtro) {
      const desc = document.getElementById('otro_descripcion');
      const num  = document.getElementById('otro_personalizado');
      desc.disabled = !chkOtro.checked;
      num.disabled  = !chkOtro.checked;
    }


    // --- Validar formulario ---
    const validar = () => {
      const nombre = document.getElementById('nombre_espacio').value.trim();
      const cap = parseInt(document.getElementById('capacidad_espacio').value, 10);


      // Validar nombre del espacio
       if (nombre.length < 3 || nombre.length > 20) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'El nombre del espacio debe tener entre 3 y 20 caracteres.' });
        return false;}

      // Validar capacidad (1–100)
      if (isNaN(cap) || cap < 1 || cap > 100) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: 'La capacidad debe ser un número entre 1 y 100.' });
        return false;}

      return true;
    };

    // --- Preparar nuevo espacio ---
    // window.prepararNuevoEspacio -> pone la aplicación en modo creación. Limpia formularios y estado, muestra el paso 1 del modal y oculta el paso 2.
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


    // Paso dos despues de crear espacio
    btnSiguiente.addEventListener('click', handleBtnPrincipal);

    // --- Guardar atributos ---
    btnGuardarAtributos.addEventListener('click', () => {
      // Guardar lista de campos deshabilitados para restaurarlos luego
      const deshabilitadosAntes = [...document.querySelectorAll('#formPaso2 [disabled]')];

      // Habilitar temporalmente todos los campos antes de enviar
      document.querySelectorAll('#formPaso2 [disabled]').forEach(el => el.disabled = false);

      // Validar solo los inputs de cantidad ACTIVOS cuyos checkboxes estén marcados
      const checksActivos = document.querySelectorAll('#formPaso2 .toggleCantidad:checked');
      for (const chk of checksActivos) {
        const targetId = chk.dataset.target;
        const input = document.getElementById(targetId);
        if (!input) continue; // seguridad

        const valor = parseInt(input.value, 10);
        if (isNaN(valor) || valor < 1 || valor > 100) {
          const label = chk.closest('.row')?.querySelector('.col-4.text-end')?.textContent?.trim() || 'atributo';
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `La cantidad del atributo "${label}" debe ser un número entre 1 y 100.`
          }).then(() => {
            // Restaurar el estado original
            deshabilitadosAntes.forEach(el => el.disabled = true);
          });
          return; // detener validación
        }
      }

      // Si todo está OK, enviamos
      const fd = new FormData(paso2);
      fd.append('accion', 'atributos');
      fd.append('id_espacio', idEspacioCreado);

      fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
          if (d.type === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Éxito',
              text: d.message
            }).then(() => location.reload()); // solo recarga si fue correcto
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: d.message
            }).then(() => {
              // Restaurar estado original si backend falla
              deshabilitadosAntes.forEach(el => el.disabled = true);
            });
          }
        })
        .catch(() => {
          Swal.fire('Error', 'No se pudieron guardar los atributos', 'error');
          deshabilitadosAntes.forEach(el => el.disabled = true);
        });
    });


    // --- Eliminar ---
    document.addEventListener('click', e => {
      const btn = e.target.closest('.eliminar-espacio-boton');
      if (!btn) return;
      e.stopPropagation();
      const id = btn.dataset.id;

      Swal.fire({
        title: i18next.t('deleteFacility'),
        text: i18next.t('actionNotBeUndone'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: i18next.t('yesDelete'),
        cancelButtonText: i18next.t('cancel')
      }).then(res => {
        if (!res.isConfirmed) return;
        const fd = new FormData();
        fd.append('accion', 'eliminar');
        fd.append('id_espacio', id);
        fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
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

  // --- Habilitar/deshabilitar inputs al marcar ---
  document.querySelectorAll('.toggleCantidad').forEach(chk => {
    chk.addEventListener('change', e => {
      const targetId = chk.dataset.target;
      const target = document.getElementById(targetId);

      if (targetId === 'otro_personalizado') {
        const desc = document.getElementById('otro_descripcion');
        if (chk.checked) {
          target.disabled = false;
          desc.disabled = false;
          target.classList.remove('bg-light', 'border-0');
          desc.classList.remove('bg-light', 'border-0');
        } else {
          target.disabled = true;
          desc.disabled = true;
          target.value = '';
          desc.value = '';
          target.classList.add('bg-light', 'border-0');
          desc.classList.add('bg-light', 'border-0');
        }
      } else {
        if (chk.checked) {
          target.disabled = false;
          target.classList.remove('bg-light', 'border-0');
        } else {
          target.disabled = true;
          target.value = '';
          target.classList.add('bg-light', 'border-0');
        }
      }
    });
  });

  // === VALIDACIONES ===
  const inputDescripcion = document.getElementById('otro_descripcion');

  // --- Solo letras y espacios para "Otro" ---
  if (inputDescripcion) {
    inputDescripcion.addEventListener('input', e => {
      const valorAnterior = inputDescripcion.value;
      const soloLetras = valorAnterior.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
      if (valorAnterior !== soloLetras) {
        inputDescripcion.value = soloLetras;
        Swal.fire({
          icon: 'warning',
          title: 'Entrada inválida',
          text: 'Solo se permiten letras y espacios en el campo "Otro".',
          confirmButtonColor: '#3085d6'
        });
      }
    });
  }

  // --- Validar antes de guardar atributos ---
  const validarCampos = () => {
    const desc = inputDescripcion?.value.trim() || '';
    const todosLosNumeros = document.querySelectorAll('#formAtributos input[type="number"]:not([disabled])');

    // Validar nombre “Otro”
    const chkOtro = document.querySelector('.toggleCantidad[data-target="otro_personalizado"]');
    if (chkOtro && chkOtro.checked) {
      if (desc.length < 3 || desc.length > 20) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'El nombre del atributo "Otro" debe tener entre 3 y 20 letras.'
        });
        return false;
      }
    }

    // Validar todas las cantidades (1–100)
    for (const input of todosLosNumeros) {
      const valor = parseInt(input.value, 10);
      if (isNaN(valor) || valor < 1 || valor > 100) {
        const label = input.closest('.row')?.querySelector('.col-4.text-end')?.textContent?.trim() || 'atributo';
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: `La cantidad del atributo "${label}" debe ser un número entre 1 y 100.`
        });
        return false;
      }
    }

    return true;
  };

  const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarAtributos'));
  document.getElementById('btnEditar')?.addEventListener('click', () => modalEditar.show());

  // --- Guardar atributos ---
  document.getElementById('guardarAtributosBtn')?.addEventListener('click', () => {
    if (!validarCampos()) return;

    const fd = new FormData(document.getElementById('formAtributos'));
    fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(d => {
        Swal.fire(d.type === 'success' ? 'Guardado' : 'Error', d.message, d.type)
          .then(() => { if (d.type === 'success') location.reload(); });
      })
      .catch(() => Swal.fire('Error', 'No se pudo guardar los atributos', 'error'));
  });

  // --- Eliminar ---
  document.getElementById('btnEliminar')?.addEventListener('click', () => {
    Swal.fire({
      title: i18next.t('deleteFacility'),
      text: i18next.t('actionNotBeUndone'),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: i18next.t('yesDelete'),
      cancelButtonText: i18next.t('cancel')
    }).then(res => {
      if (!res.isConfirmed) return;

      const fd = new FormData();
      fd.append('accion', 'eliminar');
      fd.append('id_espacio', document.querySelector('[name="id_espacio"]').value);

      fetch('./../espacio/espacio-accion.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
          const tipoEspacio = document.getElementById('tipo_espacio_actual')?.value?.toLowerCase() || '';
          let redirect = './../espacio/adscripto-espacio.php';

          if (tipoEspacio.includes('aula')) redirect = './../espacio/index-aula.php';
          else if (tipoEspacio.includes('salon')) redirect = './../espacio/index-salon.php';
          else if (tipoEspacio.includes('laboratorio')) redirect = './../espacio/index-laboratorio.php';

          Swal.fire(
            d.type === 'success' ? 'Eliminado' : 'Error',
            d.message,
            d.type
          ).then(() => {
            if (d.type === 'success') window.location.href = redirect;
          });
        })
        .catch(() => Swal.fire('Error', 'No se pudo eliminar el espacio', 'error'));
    });
  });
}
});