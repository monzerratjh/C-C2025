domForm.addEventListener('submit', function(event){
    event.preventDefault(); // Evitamos el envío normal

    const cedula = document.getElementById('cedula').value.trim();
    const password = document.getElementById('password').value.trim();

    // Validaciones
    if (!validarCampos(cedula, password)) return;
    if (!validarCedula(cedula)) return;
    if (!validarConstrasenia(password)) return;

    const formData = new FormData(this);

    fetch(this.action, {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Redirigir según rol
            // Si el login fue exitoso:
            // redirige automáticamente al usuario a la página correspondiente
            // según su rol (adscripto, docente o secretario)
            window.location.href = data.redirect;
        } else {
            alert(data.message); // Mostrar error
        }
    })
    .catch(err => console.error(err));
});
