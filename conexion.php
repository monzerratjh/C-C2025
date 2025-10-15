<?php

function conectar_bd(){

$servidor = "localhost";
$bd = "db_CoffeeAndCode";
$usuario = "CoffeeAndCode";
$pass = "coffeandcode2025";

//definir la conexion usando las variables.
$conn = mysqli_connect($servidor, $usuario, $pass, $bd); 


// Comprobar la conexión
if (!$conn) {
    Header("Error de conexion " . mysqli_connect_error());
    exit;
}
//echo "Conectado correctamente <hr>";

//devuelvo la conexion  
return $conn;
 
}


$con = conectar_bd();

?>