//MENSAJES VISUALES DEL PHP

document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);

    if (params.has("error")) {
        const error = params.get("error");
        switch(error) {
            case "CamposVacios":
                return alertSwal('Los campos no puede estar vacíos.', 'Por favor intente de nuevo');
            case "CiInvalida":
                return alertSwal('La cédula es inválida', 'Debe tener 8 caracteres.');
            case "TelefonoInvalido":
                return alertSwal('El teléfono es inválido', 'Debe tener 9 caracteres.');
            case "ContraseniaInvalida":
                return alertSwal('Contraseña inválida.', 'Debe tener entre 8 a 20 caracteres, al menos una mayúscula, una minúscula y un número.');
            case "NombreInvalido":
                return alertSwal('Nombre inválido', 'El nombre debe contener solo letras y tener entre 3 y 30 caracteres.');
            case "ApellidoInvalido":
                return alertSwal('Apellido inválido', 'El apellido debe contener solo letras y tener entre 3 y 30 caracteres.');
            case "UsuarioYaExistente":
                return alertSwal('El usuario ya existe.', 'Intente nuevamente.');
        }
    }

    if (params.has("msg")) {
        const msg = params.get("msg");
        switch(msg) {
            case "InsercionExitosa":
                return alertSuccess('Creación de Usuario Exitosa');
            case "EdicionExitosa":
                return alertSuccess('¡Edición Exitosa!');
            case "EliminacionExitosa":
                return alertSuccess('¡Eliminación Exitosa!');
        }
    }
});

function alertSwal(title, text) {
    Swal.fire({ icon: 'error', title, text, confirmButtonColor: '#d33' });
}

function alertSuccess(title) {
    Swal.fire({ icon: 'success', title, confirmButtonColor: 'rgba(95, 102, 207, 1)' });
}
function confirmDelete(id_usuario) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'rgba(95, 102, 207, 1)',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirige a tu archivo de eliminación
            window.location.href = `delete_user_secretario.php?id_usuario=${id_usuario}`;
        }
    });
}
