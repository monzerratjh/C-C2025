<?php 
require("../conexion.php");
$con = conectar_bd();

// Verificamos que los datos vienen por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cedula         = $_POST['cedula'] ?? ''; //"Si la variable existe y no es null, úsala; si no, usa este valor por defecto".
    $contrasenia    = $_POST['password'] ?? '';
    $rolFormulario  = strtolower($_POST['rol'] ?? ''); // lowercase para evitar problemas. Convierte todo el texto a minúsculas.

    // llamamos la funcion *logear
    $respuesta = logear($con, $cedula, $contrasenia, $rolFormulario);

    // Devolvemos respuesta en JSON
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

// Esta función busca al usuario en la base de datos por su cédula y devuelve todos sus datos (nombre, apellido, email, etc.) para poder usarlos después en la sesión o en el home del usuario.
function traer_datos_usuario($con, $cedula) {
    $sql = "SELECT id_usuario, nombre_usuario, apellido_usuario, gmail_usuario,
                   telefono_usuario, ci_usuario, contrasenia_usuario, cargo_usuario
            FROM usuario WHERE ci_usuario = ?";


        // stmt => statement (sentencia preparada) que devuelve la función mysqli_prepare()
    $stmt = mysqli_prepare($con, $sql); 

    /*  
        función de PHP/MySQLi que “vincula” (bind) las variables PHP a los parámetros de una sentencia preparada ($stmt).

        "s" -> string: cadena de tipos que indica el tipo de dato de cada parámetro que vas a pasar. al ser un solo parámetro (ci_usuario), que es texto: "s". 
*/  
    mysqli_stmt_bind_param($stmt, "s", $cedula); // vincula el valor de $cedula
    
    mysqli_stmt_execute($stmt); // ejecuta con el dato ya asociado

    $resultado = mysqli_stmt_get_result($stmt);

    /* 
        función de PHP/MySQLi que:
            - toma el siguiente registro (fila) del resultado de una consulta SQL.
            - devuelve ese registro como un array asociativo [cada columna de la tabla se guarda en el array usando el nombre de la columna como CLAVE].

*/  

    return mysqli_fetch_assoc($resultado) ?: null; //si la expresión de la izquierda es verdadera, devuélvela; si no, devuelve lo de la derecha
}


function logear($con, $cedula, $contrasenia, $rolFormulario) {
    $datos_usr = traer_datos_usuario($con, $cedula);

    if (!$datos_usr) {
        return ['success' => false, 'message' => 'Usuario no encontrado'];
    }

    // Verificar contraseña
    if (!password_verify($contrasenia, $datos_usr['contrasenia_usuario'])) {
        return ['success' => false, 'message' => 'Contraseña incorrecta'];
    }

    
    // Verificar rol
    $rolReal = strtolower($datos_usr['cargo_usuario']);
    if ($rolFormulario !== $rolReal) {
        return ['success' => false, 'message' => "El cargo seleccionado no coincide con el registrado"];
    }

    // Inicio de sesión
    session_start();
    $_SESSION['id_usuario']     = $datos_usr['id_usuario'];
    $_SESSION['ci_usuario']     = $datos_usr['ci_usuario'];
    $_SESSION['nombre_usuario'] = $datos_usr['nombre_usuario'];
    $_SESSION['rol']            = $rolReal;

    // guardar el ID específico según el rol 
    if ($rolReal === 'secretario') {
        $sql = "SELECT id_secretario FROM secretario WHERE id_usuario = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $datos_usr['id_usuario']);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $_SESSION['id_secretario'] = $res['id_secretario'] ?? null;
    }

    if ($rolReal === 'docente') {
        $sql = "SELECT id_docente FROM docente WHERE id_usuario = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $datos_usr['id_usuario']);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $_SESSION['id_docente'] = $res['id_docente'] ?? null;
    }

    if ($rolReal === 'adscripto') {
        $sql = "SELECT id_adscripto FROM adscripto WHERE id_usuario = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $datos_usr['id_usuario']);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $_SESSION['id_adscripto'] = $res['id_adscripto'] ?? null;
    }
    
    // Redirección según rol
    $url = '';
    if ($rolReal === 'adscripto') {
        $url = '/users/adscripto/adscripto-bienvenida.php'; 
    } elseif ($rolReal === 'docente') {
        $url = '/users/docente/docente-bienvenida.php'; 
    } elseif ($rolReal === 'secretario')$url = '/users/secretario/secretario-bienvenida.php';

    return ['success' => true, 'redirect' => $url];
}