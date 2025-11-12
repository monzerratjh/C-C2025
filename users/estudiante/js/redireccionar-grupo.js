// Creamos función
// selecciona el input en donde el estudiante selecciona su grupo
//change se usa al salir del campo o confirmar la selección.
document.getElementById('grupoInput').addEventListener('change', function() {

  // Obtenemos el valor del input y quitamos espacios sobrantes (trim saca los espacios)
  const inputValue = this.value.trim();

  // Obtenemos el datalist y sus opciones
  const datalist = document.getElementById('lista-grupos');
  const options = datalist.options; // devuelve array de las option

  let idGrupoSeleccionado = null;

  // busca el ID_GRUPO correspondiente al valor ingresado
  //cuando se encuentra se gusrada en la variable
  for (let i = 0; i < options.length; i++) {
    if (options[i].value === inputValue) {
      idGrupoSeleccionado = options[i].dataset.id;
      break;
    }
  }

  // Si se encuenrta un id válido, redirige dinámicamente
  if (idGrupoSeleccionado) {
    // Redirige a una página PHP pasando el id del grupo por la URL
    window.location.href = `./horario/horario-estudiante.php?id_grupo=${idGrupoSeleccionado}`;
  } else {
    console.log("Opción no válida o grupo no encontrado");
  }
});
