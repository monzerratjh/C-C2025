// Creamos función
document.getElementById('grupoInput').addEventListener('change', function() {
  // Obtenemos el valor del input y quitamos espacios sobrantes
  const inputValue = this.value.trim();

  // Obtenemos el datalist y sus opciones
  const datalist = document.getElementById('lista-grupos');
  const options = datalist.options;

  let idGrupoSeleccionado = null;

  // bsuca el ID_GRUPO correspondiente al valor ingresado
  for (let i = 0; i < options.length; i++) {
    if (options[i].value === inputValue) {
      idGrupoSeleccionado = options[i].dataset.id;
      break;
    }
  }

  // Si sse encuenrta un id válido, redirige dinámicamente
  if (idGrupoSeleccionado) {
    // Redirige a una página PHP pasando el id del grupo por la URL
    window.location.href = `cargar-hora.php?id_grupo=${idGrupoSeleccionado}`;
  } else {
    console.log("Opción no válida o grupo no encontrado");
  }
});