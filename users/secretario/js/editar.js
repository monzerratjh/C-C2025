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
function cargarEditarHorario(id_horario, id_grupo, dia, hora_inicio, hora_fin, turno) {
    document.getElementById('accionHorario').value = 'editar';
    document.getElementById('id_horario').value = id_horario;
    document.getElementById('id_grupo').value = id_grupo;
    document.getElementById('dia').value = dia;
    document.getElementById('hora_inicio').value = hora_inicio;
    document.getElementById('hora_fin').value = hora_fin;
    document.getElementById('turno').value = turno;
}

