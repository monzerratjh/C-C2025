<?php 
include('./../../../conexion.php');
$con = conectar_bd();

$id_espacio = $_GET['id_espacio'] ?? null;

if (!$id_espacio) {
  echo "<script>alert('Error: Espacio no especificado.'); window.history.back();</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asignar los valores recibidos del formulario
    $atributos = [
        'Mesas' => $_POST['mesas'] ?? 0,
        'Sillas' => $_POST['sillas'] ?? 0,
        'Proyector' => $_POST['proyector'] ?? 0,
        'Televisor' => $_POST['televisor'] ?? 0,
        'Aire Acondicionado' => $_POST['aire_acondicionado'] ?? 0
    ];

    // Eliminar todos los atributos anteriores del espacio
    $del = $con->prepare("DELETE FROM espacio_atributo WHERE id_espacio = ?");
    $del->bind_param("i", $id_espacio);
    $del->execute();

    // Insertar los atributos nuevos
    foreach ($atributos as $nombre => $cantidad) {
        if ($cantidad > 0) {
            $stmt = $con->prepare("
                INSERT INTO espacio_atributo (id_espacio, nombre_atributo, cantidad_atributo)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("isi", $id_espacio, $nombre, $cantidad);
            $stmt->execute();
        }
    }

    echo "<script>alert('Atributos guardados correctamente'); window.location.href='adscripto-espacio.php';</script>";
}
?>