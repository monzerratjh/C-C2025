<?php require("connection.php");

$con = conectar_bd();

// Verificamos que los datos vienen por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cedula = $_POST['cedula'] ?? ''; //"Si la variable existe y no es null, úsala; si no, usa este valor por defecto".

    $contrasenia = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';

    // Llamamos a la función login
    $respuesta = logear($con, $cedula, $contrasenia, $rol);

    // Devolvemos respuesta en JSON
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

// Esta función busca al usuario en la base de datos por su cédula
// y devuelve todos sus datos (nombre, apellido, email, etc.)
// para poder usarlos después en la sesión o en el home del usuario.
function traer_datos_usuario($con, $cedula){
    $sql = "SELECT * FROM usuario WHERE ci_usuario = '$cedula'";
    $resultado = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($resultado);

    if(mysqli_num_rows($resultado)>0){
        return [
            'id' => $row['id_usuario'],
            'nombre' => $row['nombre_usuario'],
            'apellido' => $row['apellido_usuario'],
            'email'=> $row['gmail_usuario'],
            'telefono'=> $row['telefono_usuario'],
            'cedula'=> $row['ci_usuario'],
            'contrasenia' => $row['contraseña_usuario']
        ];
     } else{
        return null;
    }
}

function logear($con, $cedula, $contrasenia, $rol) {
    $datos_usr = traer_datos_usuario($con, $cedula);

    if (!$datos_usr) {
        return ['success' => false, 'message' => 'Usuario no encontrado'];
    }

    if (!password_verify($contrasenia, $datos_usr['contrasenia'])) {
        return ['success' => false, 'message' => 'Contraseña incorrecta']; 
        //permite que JS muestre un alert o mensaje sin recargar la página.
    }

    // Inicio de sesión
    session_start();
    $_SESSION['ci_usuario'] = $cedula;
    $_SESSION['nombre_usuario'] = $datos_usr['nombre'];
    $_SESSION['rol'] = $rol;

    // Redirigir según rol
    $url = '';
    if ($rol === 'adscripto') $url = 'adscripto-bienvenida.php';
    elseif ($rol === 'docente') $url = '';
    elseif ($rol === 'secretario') $url = 'secretario-bienvenida.php';

    return ['success' => true, 'redirect' => $url];
}