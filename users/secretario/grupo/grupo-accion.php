<?php
include('../../../conexion.php');
$con = conectar_bd();

session_start(); // Inicia o continúa la sesión

// Recuperamos el id_secretario de la sesión del usuario logueado.
// Si no está definido, lo dejamos como null para evitar errores.
$id_secretario = $_SESSION['id_secretario'] ?? null;

header("Content-Type: application/json"); // Todas las respuestas serán JSON


/* Recibimos los datos del formulario:
    Si $_POST['accion'] existe y no es null, lo asigna.
    
    Si no existe usa '' (valor por defecto).

    es lo mismo que si fuese:
            if (isset($_POST['accion'])) {
                $accion = $_POST['accion'];
            } else {
                $accion = '';
            }
    */
$accion       = $_POST['accion'] ?? '';
$id_grupo     = $_POST['id_grupo'] ?? null; //?? es el operador null coalescing, que dice: si no existe el valor, usar el valor de la derecha (por ejemplo '' o null).
$nombre       = $_POST['nombre'] ?? ''; //si el campo nombre no viene en el POST, se asigna '' (vacío)
$orientacion  = $_POST['orientacion'] ?? '';
$turno        = $_POST['turno'] ?? '';
$cantidad     = $_POST['cantidad'] ?? '';
$id_adscripto = $_POST['id_adscripto'] ?? '';

// Obtener orientaciones válidas
$orientacionesValidas = obtenerOrientacionesValidas($con);


// TRY intenta ejecutar las acciones que podrían fallar
try {
    if($accion === 'insertar') {
        
        // Validar nombre 
        if (nombreGrupoExiste($con, $nombre)) {
            echo json_encode([
                "type" => "error",
                "message" => "El nombre de grupo '$nombre' ya existe."
            ]);
            exit;
        }

        // Validar formato del nombre
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 \-_]+$/u', $nombre)) {
            echo json_encode([
                "type" => "error",
                "message" => "El nombre del grupo solo puede contener letras, números y los símbolos '-', '_'."
            ]);
            exit;
        }

        // Validar orientación (independientemente a mayúsculas/tildes)
        $orientacionValida = validarOrientacion($orientacion, $orientacionesValidas);
        if (!$orientacionValida) {
            echo json_encode([
                "type" => "error",
                "message" => "La orientación '$orientacion' no es válida. Debe coincidir con una de las opciones del sistema."
            ]);
            exit;
        }

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
        
        // Validar formato del nombre
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 \-_]+$/u', $nombre)) {
            echo json_encode([
                "type" => "error",
                "message" => "El nombre del grupo solo puede contener letras, números y los símbolos '-', '_'."
            ]);
            exit;
        }

        // validar orientación
        $orientacionValida = validarOrientacion($orientacion, $orientacionesValidas);
        if (!$orientacionValida) {
            echo json_encode([
                "type" => "error",
                "message" => "La orientación '$orientacion' no es válida. Debe coincidir con una de las opciones del sistema."
            ]);
            exit;
        }
        
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

    AJAX -> (Asynchronous JavaScript and XML) es una técnica que permite que tu página se comunique con el servidor sin recargar toda la página.Hoy en día se usa JSON
*/

// Normaliza texto (minúsculas y sin tildes)
function normalizarTexto($texto) {
    // Convierte a minúsculas
    $texto = mb_strtolower($texto, 'UTF-8');

    // Sustituye tildes, diéresis y eñes manualmente
    $buscar  = ['á','é','í','ó','ú','ü','ñ'];
    $reempl  = ['a','e','i','o','u','u','n'];
    $texto = str_replace($buscar, $reempl, $texto);

    // También elimina posibles espacios extras
    return trim($texto);
};


// obtiene todas las orientaciones válidas desde el ENUM de la BD
function obtenerOrientacionesValidas($con) {
    $resEnum = $con->query("SHOW COLUMNS FROM grupo LIKE 'orientacion_grupo'");
    $rowEnum = $resEnum->fetch_assoc();
    preg_match_all("/'([^']+)'/", $rowEnum['Type'], $coincidencias);
    return $coincidencias[1];
}


//  Valida si una orientación enviada por el usuario es válida (ignora mayúsculas y tildes)
function validarOrientacion($orientacionUsuario, $orientacionesValidas) {
    $normalizadoUsuario = normalizarTexto($orientacionUsuario);

    foreach ($orientacionesValidas as $valida) {
        if ($normalizadoUsuario === normalizarTexto($valida)) {
            return $valida; // devuelve la versión original (exacta) del ENUM
        }
    }
    return false; // No coincide con ninguna orientación
}


// Verifica si un nombre de grupo ya existe
function nombreGrupoExiste($con, $nombre) {
    $stmt = $con->prepare("SELECT COUNT(*) AS total FROM grupo WHERE nombre_grupo = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    return $resultado['total'] > 0;
}

