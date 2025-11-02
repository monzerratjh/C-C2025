<?php
session_start();
session_unset();
session_destroy();

// Detectar automáticamente la raíz del proyecto
$rutaIndex = str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/')) . 'index.php';
header("Location: $rutaIndex");
exit();
?>
