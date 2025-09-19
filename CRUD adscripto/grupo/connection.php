<?php
function connection(){
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "itsplanner"; 
    $conn = new mysqli($host,$user,$pass,$db);

    if($conn->connect_error){
        die("Conexión fallida: ".$conn->connect_error);
    }
    $conn->set_charset("utf8");
    return $conn;
}
?>
