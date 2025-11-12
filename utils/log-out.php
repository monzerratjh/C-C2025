<?php
session_start(); //inicia o reanuda sesion existente
session_unset(); //elimina variables almacenadas en $_SESSION
session_destroy();//elimina completamente la sesión del servidor.
header('Location: /index.php'); //el encabezado lo redirige al index.php
exit(); // se detiene la ejecucion
?>