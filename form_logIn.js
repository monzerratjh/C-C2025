const domForm = document.querySelector('form');

function validarCampos(cedula, password) {
    if (!cedula || !password) {
        alert("Por favor, complete todos los campos");
        return false;
    }
    return true;
}

function validarCedula(cedula) {
    if (cedula.length !== 8 || isNaN(cedula)) {
        alert("La cédula debe tener 8 números válidos");
        return false;
    }
    return true;
}

function validarConstrasenia (password){
    if (password.length < 5) {
        alert("La contraseña debe tener al menos 5 caracteres.");
            return false; // Evitar el envío del formulario
        }
        if (!/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
        alert("La contraseña debe contener al menos una letra mayúscula y un número.");
            return false; // Evitar el envío del formulario
        }
    return true;
}

domForm.addEventListener('submit', function(event){
    const cedula = document.getElementById('cedula').value.trim();
    const password = document.getElementById('password').value.trim();
    const rol = this.rol.value;

    // Validaciones
    if (!validarCampos(cedula, password)) return;
    if (!validarCedula(cedula)) return;
    if (!validarConstrasenia(password)) return;

    const formData = new FormData(this);

    fetch("", {
        method: "POST",
        body: formDataConversion
    })
    .catch(err => console.error(err)); // Solo captura errores de red
});
