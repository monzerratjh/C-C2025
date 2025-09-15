<?php

function connection(){
    $host = "localhost";
    $user = "root";
    $pass = "";
    $bd = "itsplanner_bd_prueba_datos";

    $connect=mysqli_connect($host, $user, $pass);

    mysqli_select_db($connect, $bd);

    return $connect;

}

?>