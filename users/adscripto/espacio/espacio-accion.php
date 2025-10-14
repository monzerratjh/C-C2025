<?php
include('./../../../conexion.php');
$con = conectar_bd();

session_start();
$id_adscripto = $_SESSION['id_adscripto'] ?? null;

header("Content-Type: application/json");

$accion          = $_POST['accion'] ?? '';
$id_espacio      = $_POST['id_espacio'] ?? null;
$nombre          = $_POST['nombre_espacio'] ?? '';
$capacidad       = $_POST['capacidad_espacio'] ?? '';
$historial       = $_POST['historial_espacio'] ?? '';
$tipo            = $_POST['tipo_espacio'] ?? '';
$disponibilidad  = $_POST['disponibilidad_espacio'] ?? 'Libre';

// Obtener tipos válidos desde ENUM
$tiposValidos = obtenerTiposValidos($con);

// TRY para manejar errores
try {
    // --------------------------------------------------------
    // INSERTAR
    // --------------------------------------------------------
    if ($accion === 'insertar') {

        // Validar nombre
        if (nombreEspacioExiste($con, $nombre)) {
            echo json_encode(["type" => "error", "message" => "El nombre de espacio '$nombre' ya existe."]);
            exit;
        }

        // Validar formato del nombre (solo letras, números, espacios y símbolos permitidos)
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9° \-_]+$/u', $nombre)) {
            echo json_encode([
                "type" => "error",
                "message" => "El nombre del espacio solo puede contener letras, números y los símbolos '°', '-', '_'."
            ]);
            exit;
        }

        // Validar capacidad
        if (!is_numeric($capacidad) || $capacidad < 1 || $capacidad > 100) {
            echo json_encode(["type" => "error", "message" => "La capacidad debe ser un número entre 1 y 100."]);
            exit;
        }

        // Validar tipo de espacio
        $tipoValido = validarTipo($tipo, $tiposValidos);
        if (!$tipoValido) {
            echo json_encode([
                "type" => "error",
                "message" => "El tipo de espacio '$tipo' no es válido. Debe coincidir con uno de los tipos del sistema."
            ]);
            exit;
        }

        // Inserción
        $stmt = $con->prepare("INSERT INTO espacio (nombre_espacio, capacidad_espacio, historial_espacio, tipo_espacio, disponibilidad_espacio) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $nombre, $capacidad, $historial, $tipoValido, $disponibilidad);
        if (!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type" => "success", "message" => "Espacio agregado correctamente."]);
    }

    // --------------------------------------------------------
    // EDITAR
    // --------------------------------------------------------
    elseif ($accion === 'editar') {

        // Validar formato del nombre
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9° \-_]+$/u', $nombre)) {
            echo json_encode([
                "type" => "error",
                "message" => "El nombre del espacio solo puede contener letras, números y los símbolos '°', '-', '_'."
            ]);
            exit;
        }

        // Validar capacidad
        if (!is_numeric($capacidad) || $capacidad < 1 || $capacidad > 100) {
            echo json_encode(["type" => "error", "message" => "La capacidad debe ser un número entre 1 y 100."]);
            exit;
        }

        // Validar tipo
        $tipoValido = validarTipo($tipo, $tiposValidos);
        if (!$tipoValido) {
            echo json_encode(["type" => "error", "message" => "El tipo de espacio '$tipo' no es válido."]);
            exit;
        }

        $stmt = $con->prepare("UPDATE espacio SET nombre_espacio=?, capacidad_espacio=?, historial_espacio=?, tipo_espacio=?, disponibilidad_espacio=? WHERE id_espacio=?");
        $stmt->bind_param("sisssi", $nombre, $capacidad, $historial, $tipoValido, $disponibilidad, $id_espacio);
        if (!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type" => "success", "message" => "Espacio actualizado correctamente."]);
    }

    // --------------------------------------------------------
    // ELIMINAR
    // --------------------------------------------------------
    elseif ($accion === 'eliminar') {
        $stmt = $con->prepare("DELETE FROM espacio WHERE id_espacio=?");
        $stmt->bind_param("i", $id_espacio);
        if (!$stmt->execute()) throw new Exception($stmt->error);

        echo json_encode(["type" => "success", "message" => "Espacio eliminado correctamente."]);
    }

    // --------------------------------------------------------
    // ACCIÓN NO RECONOCIDA
    // --------------------------------------------------------
    else {
        throw new Exception("Acción no reconocida o inválida.");
    }

} catch (Exception $e) {
    echo json_encode(["type" => "error", "message" => "Error: " . $e->getMessage()]);
}

// --------------------------------------------------------
// FUNCIONES AUXILIARES
// --------------------------------------------------------

// Normaliza texto para comparar sin tildes ni mayúsculas
function normalizarTexto($texto) {
    $texto = mb_strtolower($texto, 'UTF-8');
    $buscar = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'];
    $reempl = ['a', 'e', 'i', 'o', 'u', 'u', 'n'];
    return trim(str_replace($buscar, $reempl, $texto));
}

// Obtiene tipos válidos (ENUM) desde la BD
function obtenerTiposValidos($con) {
    $resEnum = $con->query("SHOW COLUMNS FROM espacio LIKE 'tipo_espacio'");
    $rowEnum = $resEnum->fetch_assoc();
    preg_match_all("/'([^']+)'/", $rowEnum['Type'], $coincidencias);
    return $coincidencias[1];
}

// Valida si el tipo enviado coincide con los del ENUM
function validarTipo($tipoUsuario, $tiposValidos) {
    $normalizadoUsuario = normalizarTexto($tipoUsuario);
    foreach ($tiposValidos as $valido) {
        if ($normalizadoUsuario === normalizarTexto($valido)) {
            return $valido;
        }
    }
    return false;
}

// Verifica si ya existe un espacio con ese nombre
function nombreEspacioExiste($con, $nombre) {
    $stmt = $con->prepare("SELECT COUNT(*) AS total FROM espacio WHERE nombre_espacio = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    return $resultado['total'] > 0;
}
?>
