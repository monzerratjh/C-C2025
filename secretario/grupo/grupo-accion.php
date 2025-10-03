<?php
include('../../conexion.php');
$con = conectar_bd();
session_start();

/* Recibimos los datos del formulario:
    Si $_POST['accion'] existe y no es null, lo asigna.
    
    Si no existe usa '' (valor por defecto).
    */
$accion       = $_POST['accion'] ?? '';
$id_grupo     = $_POST['id_grupo'] ?? null; //?? es el operador null coalescing, que dice: si no existe el valor, usar el valor de la derecha (por ejemplo '' o null).
$nombre       = $_POST['nombre'] ?? ''; //si el campo nombre no viene en el POST, se asigna '' (vacío)
$orientacion  = $_POST['orientacion'] ?? '';
$turno        = $_POST['turno'] ?? '';
$cantidad     = $_POST['cantidad'] ?? '';
$id_adscripto = $_POST['id_adscripto'] ?? '';
$id_secretario = $_SESSION['id_secretario'] ?? 1; // fallback a 1 si no hay sesión


// Se indica que la respuesta sera JSON, no HTML
header("Content-Type: application/json");

// Se indica que la respuesta será JSON, no HTML.
$response = ["type" => "success", "message" => ""]; //Esto es lo que el JavaScript con fetch() va a leer y mostrar con SweetAlert.
// $message = '';  Texto que se mostrará.
// $type = 'success'; Tipo de alerta (success o error).


// TRY intenta ejecutar las acciones que podrían fallar
try {
    if($accion === 'insertar') {
        $stmt = $con->prepare("INSERT INTO grupo (nombre_grupo, orientacion_grupo, turno_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $nombre, $orientacion, $turno, $cantidad, $id_adscripto, $id_secretario);
        if(!$stmt->execute()) throw new Exception($stmt->error); //Si falla, se lanza una excepción con el mensaje de error.

        echo json_encode(["type" => "success", "message" => "Grupo agregado correctamente."]);
    } 
        /*
            prepare() -> Prepara la consulta SQL de forma segura, evitando inyecciones SQL.

            ? -> Son placeholders para los valores que se insertarán.

            bind_param("sssiii", ...) -> Asocia los valores de PHP a los placeholders:

            "sssiii" significa: string, string, string, int, int, int.

            execute() -> Ejecuta la consulta.
        */

    elseif($accion === 'editar') { // WHERE id_grupo=? -> Indica qué grupo se actualizará según su ID.
        $stmt = $con->prepare("UPDATE grupo SET nombre_grupo=?, orientacion_grupo=?, turno_grupo=?, cantidad_alumno_grupo=?, id_adscripto=? WHERE id_grupo=?");
        $stmt->bind_param("sssiii", $nombre, $orientacion, $turno, $cantidad, $id_adscripto, $id_grupo);
        if(!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type" => "success", "message" => "Grupo actualizado correctamente."]);
    } 
    elseif($accion === 'eliminar') {
        $stmt = $con->prepare("DELETE FROM grupo WHERE id_grupo=?");
        $stmt->bind_param("i", $id_grupo);
        if(!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type" => "success", "message" => "Grupo eliminado correctamente."]);
    } 
    else {
        throw new Exception("Acción no reconocida.");
    }
} catch(Exception $e) {
    echo json_encode(["type" => "error", "message" => "Error: ".$e->getMessage()]);
} 
// catch -> si algo falla, captura el error y guarda el mensaje en $message y el tipo como error

/*
1. En el JS alguien hace submit → se manda AJAX a grupo-accion.php.
2. PHP procesa la acción y devuelve algo como:
    { "type": "success", "message": "Grupo agregado correctamente." }
3. El JS recibe eso y muestra un SweetAlert.
4. Si es éxito → recarga la lista o cierra el modal.
   Si es error → muestra el error.

    AJAX -> AJAX (Asynchronous JavaScript and XML) es una técnica que permite que tu página se comunique con el servidor sin recargar toda la página.Hoy en día se usa JSON
*/