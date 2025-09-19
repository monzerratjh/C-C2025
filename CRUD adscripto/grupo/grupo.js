
function confirmarEliminar(id){
    if(confirm("¿Seguro quieres eliminar este grupo?")){
        document.getElementById('formEliminar'+id).submit();
    }
}

function cargarEditar(id, orientacion, turno, nombre, cantidad){
    document.getElementById('accion').value = 'editar';
    document.getElementById('id_grupo').value = id;
    document.getElementById('orientacion').value = orientacion;
    document.getElementById('turno').value = turno;
    document.getElementById('nombre').value = nombre;
    document.getElementById('cantidad').value = cantidad;
}
