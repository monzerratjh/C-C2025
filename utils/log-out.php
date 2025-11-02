<?php
session_start();
session_unset();
session_destroy();

// Construir ruta completa a la raíz del proyecto
$host = $_SERVER['HTTP_HOST']; // dbitsp.tailff9876.ts.net
$path = dirname($_SERVER['PHP_SELF']); // /CoffeeAndCode/C-C2025/users/docente/reserva
// Subir hasta la raíz del proyecto
$basePath = '/CoffeeAndCode/C-C2025/';

// Redirigir correctamente
header("Location: http://$host$basePath" . "index.php");
exit();
?>
