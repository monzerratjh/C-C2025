// ==============================
// TOGGLE DÍAS
// ==============================
document.querySelectorAll('.toggle-dia').forEach(btn => {
  btn.addEventListener('click', () => {
    const cont = btn.nextElementSibling;
    cont.style.display = cont.style.display === 'block' ? 'none' : 'block';
  });
});

const modal = new bootstrap.Modal(document.getElementById('modalHorario'));

// ==============================
// EDITAR HORARIO
// ==============================
document.querySelectorAll('.editar-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('accionForm').value = 'editar';
    document.getElementById('idHorarioAsignado').value = btn.dataset.id;
    document.getElementById('dia').value = btn.dataset.dia;
    document.getElementById('id_horario_clase').value = btn.dataset.horario;
    document.getElementById('id_gada').value = btn.dataset.gada;
    modal.show();
  });
});

// ==============================
// VALIDACIÓN FORMULARIO
// ==============================
document.addEventListener("DOMContentLoaded", () => {
  const formHorario = document.querySelector("#modalHorario form");

  formHorario.addEventListener("submit", async (event) => {
    event.preventDefault(); // evita que se recargue la página

    const formData = new FormData(formHorario);
    const dia = formData.get("dia").trim();
    const horario = formData.get("id_horario_clase").trim();
    const gada = formData.get("id_gada").trim();

    if (!dia || !horario || !gada) {
      Swal.fire({
        icon: "error",
        title: i18next.t('emptyFields'),
        text: i18next.t('pleaseFillIn'),
        confirmButtonColor: "#198754",
      });
      return;
    }

    const result = await Swal.fire({
      title: i18next.t('wantSaveChanges'),
      icon: "question",
      showCancelButton: true,
      confirmButtonText: i18next.t('yesSave'),
      cancelButtonText: i18next.t('cancel'),
      confirmButtonColor: "#198754",
    });

    if (result.isConfirmed) {
      const response = await fetch("cargar-hora-accion.php", {
        method: "POST",
        body: formData,
      });
      const data = await response.json();

      Swal.fire({
        icon: data.type,
        title: data.type === "success" ? "Éxito" : "Error",
        text: data.message,
        confirmButtonColor: "#198754",
      }).then(() => {
        if (data.type === "success") location.reload();
      });
    }
  });
});


// ==============================
// ELIMINAR HORARIO AJAX
// ==============================
document.querySelectorAll(".eliminar-btn").forEach(btn => {
  btn.addEventListener("click", async () => {
    const id = btn.dataset.id;
    const grupo = btn.dataset.grupo;

    Swal.fire({
      title: i18next.t('deleteSchedule'),
      text: i18next.t('actionNotBeUndone'),
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: i18next.t('yesDelete'),
      cancelButtonText: i18next.t('cancel'),
      confirmButtonColor: "#dc3545",
    }).then(async result => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("accion","eliminar");
        formData.append("id_horario_asignado", id);
        formData.append("id_grupo", grupo);

        try {
          const res = await fetch("cargar-hora-accion.php",{method:"POST",body:formData});
          const data = await res.json();
          Swal.fire({icon:data.type, title:data.type==='success'?'Eliminado':'Error', text:data.message})
            .then(()=> { if(data.type==='success') location.reload(); });
        } catch (err) {
          console.error(err);
          Swal.fire({icon:'error', title:'Error', text: i18next.t('scheduleNotBeUndone')});
        }
      }
    });
  });
});