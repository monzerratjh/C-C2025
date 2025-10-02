/*  ANTES DEL SWEETALERT

function confirmarEliminar(id){
    if(confirm("¿Seguro quieres eliminar este grupo?")){
        document.getElementById('formEliminar'+id).submit();
    }
}   
    
    */

function cargarEditar(id, orientacion, turno, nombre, cantidad, id_adscripto) {
    document.getElementById('accion').value = 'editar';
    document.getElementById('id_grupo').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('orientacionInput').value = orientacion;
    document.getElementById('turno').value = turno;
    document.getElementById('cantidad').value = cantidad;
    document.querySelector('select[name="id_adscripto"]').value = id_adscripto;
}
