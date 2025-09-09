<?php
    if(isset($_POST['btn-log-in'])) {
       validacion_datos_form();
    }

    function validacion_datos_form() {
        $cedula = $_POST['cedula'];
        $password = $_POST['password'];

        if($cedula == "" || empty($cedula)) {
            header('location:./index.php?message=Debe llenar el campo de cedula!');
        } else if(strlen($cedula) > 8 || strlen($cedula) < 8) {
            header('location:./index.php?message=Su c.i debe llegar al máximo de 8 numeros.');
        }

        if($password == "" || empty($password)) {
            header('location:./index.php?message=Debe llenar el campo de la contrasenia!');
        }
    }
?>