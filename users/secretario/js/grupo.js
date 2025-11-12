
    // GRUPOS
// VALIDACIÓN DEL FORMULARIO GRUPOS
function validarGrupo() {
    const nombre = document.getElementById('nombre').value.trim();
    const orientacion = document.getElementById('orientacionInput').value.trim();
    const turno = document.getElementById('turno').value;
    const cantidad = parseInt(document.getElementById('cantidad').value);

    if (nombre === '') {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: i18next.t('enterTheGroup') });
        return false;
    } else if (nombre.length > 6) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: i18next.t('groupNameMustBe')});
        return false;
    }

    if (orientacion === '') {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: i18next.t('orientationsCorrespond')  });
        return false;
    }

    if (turno === '') {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: i18next.t('selectAShift') });
        return false;
    }

    if (isNaN(cantidad) || cantidad < 1 || cantidad > 50 ) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: i18next.t('invalidNumber')  });
        return false;
    }

    return true; // Todo bien
}

// OBTENER DATOS DEL FORMULARIO
function obtenerDatosGrupo() {
    const form = document.getElementById('formGrupo');
    const formData = new FormData(form);

    // Asegurarse de que los valores sean los mismos que validamos
    formData.set("nombre", document.getElementById('nombre').value.trim());
    formData.set("orientacion", document.getElementById('orientacionInput').value.trim());
    formData.set("turno", document.getElementById('turno').value);
    formData.set("cantidad", document.getElementById('cantidad').value);

    return formData;
}

// ENVÍO DE DATOS AL PHP
function enviarGrupo(formData) {
    fetch("grupo-accion.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.type,
            title: data.type === "error" ? "Error" : "Éxito",
            text: data.message
        }).then(() => {
            if (data.type === 'success') {
                location.reload(); // recarga la lista SOLO si todo salió bien
            }
        });
    })
    .catch(err => {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: i18next.t('requestNoProcessed') });
        console.error(err);
    });
}

// EVENTO SUBMIT FORMULARIO GRUPOS
document.getElementById('formGrupo').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita envío directo

    if (validarGrupo()) {
        const formData = obtenerDatosGrupo();
        enviarGrupo(formData);
    }
});

// ELIMINAR GRUPO
document.addEventListener('click', function(e) {
    if (e.target.matches('.eliminar-grupo-btn')) {
        const id = e.target.dataset.id;
        Swal.fire({
            title: i18next.t('deleteGroup'),
            text: i18next.t('actionNotBeUndone'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: i18next.t('yesDelete'),
            cancelButtonText: i18next.t('cancel')
        }).then((result) => {
            if (result.isConfirmed) {
                const form = new FormData();
                form.append("accion", "eliminar");
                form.append("id_grupo", id);
                enviarGrupo(form);
            }
        });
    }
});


// CARGAR GRUPO PARA EDITAR
function cargarEditar(id, orientacion, turno, nombre, cantidad, id_adscripto) {
    document.getElementById('accion').value = 'editar';
    document.getElementById('id_grupo').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('orientacionInput').value = orientacion;
    document.getElementById('turno').value = turno;
    document.getElementById('cantidad').value = cantidad;
    document.querySelector('select[name="id_adscripto"]').value = id_adscripto;
}


// Evitar reenvío al recargar

if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.pathname);
}