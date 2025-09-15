<?php
include("connection.php");
$con = connection();

// Verificamos que lleguen todos los datos
if (isset($_POST['nombre_usuario'], $_POST['apellido_usuario'], $_POST['gmail_usuario'], $_POST['telefono_usuario'], $_POST['ci_usuario'])) {

    // id_usuario es AUTO_INCREMENT, no manual
    $nombre   = $_POST['nombre_usuario'];
    $apellido = $_POST['apellido_usuario'];
    $gmail    = $_POST['gmail_usuario'];
    $telefono = $_POST['telefono_usuario'];
    $cedula   = $_POST['ci_usuario'];

    // Validación: cédula 8 dígitos
    if (!preg_match('/^\d{8}$/', $cedula)) {
        die("La cédula debe tener exactamente 8 números.");
    }

    // Validación: teléfono 9 dígitos
    if (!preg_match('/^\d{9}$/', $telefono)) {
        die("El teléfono debe tener exactamente 9 números.");
    }

    // Insert en la BD
    $sql = "INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, ci_usuario) 
            VALUES ('$nombre', '$apellido', '$gmail', '$telefono', '$cedula')";

    $query = mysqli_query($con, $sql);

    if ($query) {
        // Mensaje de éxito y redirección
        echo "<script>
                alert('Usuario agregado correctamente');
                window.location.href='index.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error al insertar: " . mysqli_error($con) . "');
                window.history.back();
              </script>";
        exit();
    }

} else {
    echo "<script>
            alert('Faltan datos para crear el usuario.');
            window.history.back();
          </script>";
    exit();
}
?>
