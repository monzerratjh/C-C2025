document.addEventListener('DOMContentLoaded', () => {
    const domForm = document.getElementById('form-login-adscripto');

    domForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const cedula = document.getElementById('cedula').value.trim();
        const password = document.getElementById('password').value.trim();
        const mensajeError = document.getElementById('mensaje-error');
        mensajeError.textContent = '';

        if (!cedula || !password) {
            mensajeError.textContent = "Todos los campos son obligatorios.";
            return;
        }

        const formData = new FormData(this);

        fetch(this.action, {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                mensajeError.textContent = data.message;
            }
        })
        .catch(err => {
            console.error(err);
            mensajeError.textContent = "Error al conectar con el servidor.";
        });
    });
});
