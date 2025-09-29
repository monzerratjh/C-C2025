<?php

function conectar_bd(){
    $host = "localhost";
    $user = "root";
    $pass = "";
    $bd = "itsplanner";

    $connect=mysqli_connect($host, $user, $pass);

    mysqli_select_db($connect, $bd);
    
    // Verificar conexión
    if (!$connect) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    return $connect;

}

?>