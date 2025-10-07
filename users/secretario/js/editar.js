// ----------------------------
// CARGAR GRUPO PARA EDITAR
// ----------------------------
function cargarEditar(id, orientacion, turno, nombre, cantidad, id_adscripto) {
    document.getElementById('accion').value = 'editar';
    document.getElementById('id_grupo').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('orientacionInput').value = orientacion;
    document.getElementById('turno').value = turno;
    document.getElementById('cantidad').value = cantidad;
    document.querySelector('select[name="id_adscripto"]').value = id_adscripto;
}

// ----------------------------
// CARGAR HORARIO PARA EDITAR
// ----------------------------
function cargarEditarHorario(id, dia, inicio, fin, turno) {
    document.getElementById('accionHorario').value = 'editar';
    document.getElementById('id_horario_clase').value = id;
    document.getElementById('dia').value = dia;
    document.getElementById('hora_inicio').value = inicio;
    document.getElementById('hora_fin').value = fin;
    document.getElementById('turno').value = turno;
}
