<?php
include("conexion_BD.php");   // Incluye la conexión a la base de datos
$conn = conectar_bd();

$accion = $_POST['accion'] ?? '';

// Lista de orientaciones válidas
$orientaciones_validas = [
    "Tecnologías de la Información",
    "Tecnologías de la Información Bilingüe",
    "Finest IT y Redes",
    "Redes y Comunicaciones Ópticas",
    "Diseño Gráfico en Comunicación Visual",
    "Secretariado Bilingüe - Inglés",
    "Tecnólogo en Ciberseguridad"
];

if($accion == 'insertar'){
    $orientacion = $_POST['orientacion'];
    $turno = $_POST['turno'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $id_adscripto = $_POST['id_adscripto'];
    $id_secretario = $_POST['id_secretario'];

    // validación de orientacion
    if (!in_array($orientacion, $orientaciones_validas)) {
        die("Orientación no válida."); 
    }

    $stmt = $conn->prepare("INSERT INTO grupo (orientacion_grupo, turno_grupo, nombre_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiii",$orientacion,$turno,$nombre,$cantidad,$id_adscripto,$id_secretario);
    $stmt->execute();
    $stmt->close();
    header("Location: agregar-grupo.php?message=Grupo creado");
}

if($accion == 'editar'){
    $id = $_POST['id_grupo'];
    $orientacion = $_POST['orientacion'];
    $turno = $_POST['turno'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];

    // validación de orientación
    if (!in_array($orientacion, $orientaciones_validas)) {
        die("Orientación no válida."); 
    }

    $stmt = $conn->prepare("UPDATE grupo SET orientacion_grupo=?, turno_grupo=?, nombre_grupo=?, cantidad_alumno_grupo=? WHERE id_grupo=?");
    $stmt->bind_param("sssii",$orientacion,$turno,$nombre,$cantidad,$id);
    $stmt->execute();
    $stmt->close();
    header("Location: agregar-grupo.php?message=Grupo actualizado");
}

if($accion == 'eliminar'){
    $id = $_POST['id_grupo'];
    $stmt = $conn->prepare("DELETE FROM grupo WHERE id_grupo=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
    header("Location: agregar-grupo.php?message=Grupo eliminado");
}
?>
