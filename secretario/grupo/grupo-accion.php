<?php
include('../../conexion.php');
$con = conectar_bd();
session_start();

// Recibimos los datos del formulario
$accion       = $_POST['accion'] ?? '';
$id_grupo     = $_POST['id_grupo'] ?? null; //?? es el operador null coalescing, que dice: si no existe el valor, usar el valor de la derecha (por ejemplo '' o null).
$nombre       = $_POST['nombre'] ?? '';
$orientacion  = $_POST['orientacion'] ?? '';
$turno        = $_POST['turno'] ?? '';
$cantidad     = $_POST['cantidad'] ?? '';
$id_adscripto = $_POST['id_adscripto'] ?? '';
$id_secretario = $_SESSION['id_secretario'] ?? 1; // fallback a 1 si no hay sesión

//Se crean variables para mostrar mensajes con SweetAlert:
$message = ''; // Texto que se mostrará.
$type = 'success'; //Tipo de alerta (success o error).

try {
    if($accion === 'insertar') {
        $stmt = $con->prepare("INSERT INTO grupo (nombre_grupo, orientacion_grupo, turno_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $nombre, $orientacion, $turno, $cantidad, $id_adscripto, $id_secretario);
        if(!$stmt->execute()) {
            throw new Exception($stmt->error); //Si falla, se lanza una excepción con el mensaje de error.
        }
        $message = "Grupo agregado correctamente.";

        /*
            prepare() -> Prepara la consulta SQL de forma segura, evitando inyecciones SQL.

            ? -> Son placeholders para los valores que se insertarán.

            bind_param("sssiii", ...) -> Asocia los valores de PHP a los placeholders:

            "sssiii" significa: string, string, string, int, int, int.

            execute() -> Ejecuta la consulta.
        */

    } elseif($accion === 'editar') { // WHERE id_grupo=? -> Indica qué grupo se actualizará según su ID.
        $stmt = $con->prepare("UPDATE grupo SET nombre_grupo=?, orientacion_grupo=?, turno_grupo=?, cantidad_alumno_grupo=?, id_adscripto=? WHERE id_grupo=?");
        $stmt->bind_param("sssiii", $nombre, $orientacion, $turno, $cantidad, $id_adscripto, $id_grupo);
        if(!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $message = "Grupo actualizado correctamente.";
    } elseif($accion === 'eliminar') {
        $stmt = $con->prepare("DELETE FROM grupo WHERE id_grupo=?");
        $stmt->bind_param("i", $id_grupo);
        if(!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $message = "Grupo eliminado correctamente.";
    } else {
        throw new Exception("Acción no reconocida.");
    }
} catch(Exception $e) {
    $message = "Error: ".$e->getMessage();
    $type = 'error';
} 

/* 
    - try -> intenta ejecutar las acciones que podrían fallar
    - catch -> si algo falla, captura el error y guarda el mensaje en $message y el tipo como error
*/

// Redirigimos de vuelta con SweetAlert usando GET
header("Location: secretario-grupo.php?message=".urlencode($message)."&type=".$type);
exit;
/*
    urlencode() -> Codifica el mensaje para que se pase correctamente por la URL.

    exit; -> Termina la ejecución del script para que la redirección funcione.
*/
