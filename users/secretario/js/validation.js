const form = document.querySelector('form');
const ci_usuario = document.getElementById('ci_usuario');
const nombre_usuario = document.getElementById('nombre_usuario');
const apellido_usuario = document.getElementById('apellido_usuario');
const telefono_usuario = document.getElementById('telefono_usuario');
const gmail_usuario = document.getElementById('gmail_usuario');
const contrasenia_usuario = document.getElementById('contrasenia_usuario');

form.addEventListener('submit', function(e) {
   
});




// ----------------------------
// Evitar reenvío al recargar
// ----------------------------
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.pathname);
}