<?php
include(dirname(__FILE__).'../../../encabezado.php');
include(dirname(__FILE__).'/../../../conexion.php');
$conn = conectar_bd();

// Supongamos que los valores vienen de un formulario POST
$id_usuario = $_POST['id_usuario'];
$ci_usuario = $_POST['ci_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$gmail_usuario = $_POST['gmail_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];
$cargo_usuario = $_POST['cargo_usuario'];
$contrasenia_usuario = $_POST['contrasenia_usuario'];


// 1️⃣ Traemos el cargo anterior antes de actualizar
$sql_prev = "SELECT cargo_usuario FROM usuario WHERE id_usuario = ?";
$stmt_prev = $conn->prepare($sql_prev);
$stmt_prev->bind_param("i", $id_usuario);
$stmt_prev->execute();
$result_prev = $stmt_prev->get_result();
$prev = $result_prev->fetch_assoc();
$cargo_anterior = $prev['cargo_usuario'] ?? null;
$stmt_prev->close();


// 2️⃣ Actualizamos la tabla usuario
$stmt = $conn->prepare("UPDATE usuario
    SET ci_usuario = ?,
        nombre_usuario = ?,
        apellido_usuario = ?,
        gmail_usuario = ?,
        telefono_usuario = ?,
        cargo_usuario = ?,
        contrasenia_usuario = ?
    WHERE id_usuario = ?");
$stmt->bind_param("isssissi", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $contrasenia_usuario, $id_usuario);
$stmt->execute();
$stmt->close();


// 3️⃣ Si cambió de cargo, eliminamos de la tabla anterior e insertamos en la nueva
if ($cargo_anterior !== $cargo_usuario) {
    // Eliminamos de la tabla anterior
    switch ($cargo_anterior) {
        case "Docente":
            $conn->query("DELETE FROM docente WHERE id_usuario = $id_usuario");
            break;
        case "Adscripto":
            $conn->query("DELETE FROM adscripto WHERE id_usuario = $id_usuario");
            break;
        case "Secretario":
            $conn->query("DELETE FROM secretario WHERE id_usuario = $id_usuario");
            break;
    }


    // Insertamos en la nueva tabla
    switch ($cargo_usuario) {
        case "Docente":
            $conn->query("INSERT INTO docente (id_usuario) VALUES ($id_usuario)");
            break;
        case "Adscripto":
            $conn->query("INSERT INTO adscripto (id_usuario) VALUES ($id_usuario)");
            break;
        case "Secretario":
            $conn->query("INSERT INTO secretario (id_usuario) VALUES ($id_usuario)");
            break;
    }
}


// 4️⃣ Redirigir
header("Location: ./secretario-usuario.php");
exit;
?>


/*$stmt = $conn->prepare("UPDATE usuario
    SET ci_usuario = ?,
        nombre_usuario = ?,
        apellido_usuario = ?,
        gmail_usuario = ?,
        telefono_usuario = ?,
        cargo_usuario = ?,
        contrasenia_usuario = ?
    WHERE id_usuario = ?");
    $stmt->bind_param("isssissi", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $contrasenia_usuario, $id_usuario);
    $stmt->execute();
    $stmt->close();


    // Ahora según el cargo traemos los datos extra
    if ($cargo_usuario  === "Docente") {
        $sql_docente = "SELECT * FROM docente WHERE id_usuario = ?";
        $stmt_doc = mysqli_prepare($conn, $sql_docente);
        mysqli_stmt_bind_param($stmt_doc, "i", $id_usuario);
        mysqli_stmt_execute($stmt_doc);
        $res_doc = mysqli_stmt_get_result($stmt_doc);
        $cargo_data = mysqli_fetch_assoc($res_doc);
    } elseif ($cargo_usuario  === "Adscripto") {
        $sql_ads = "SELECT * FROM adscripto WHERE id_usuario = ?";
        $stmt_ads = mysqli_prepare($conn, $sql_ads);
        mysqli_stmt_bind_param($stmt_ads, "i", $id_usuario);
        mysqli_stmt_execute($stmt_ads);
        $res_ads = mysqli_stmt_get_result($stmt_ads);
        $cargo_data = mysqli_fetch_assoc($res_ads);
    } elseif ($cargo_usuario === "Secretario") {
        $sql_sec = "SELECT * FROM secretario WHERE id_usuario = ?";
        $stmt_sec = mysqli_prepare($conn, $sql_sec);
        mysqli_stmt_bind_param($stmt_sec, "i", $id_usuario);
        mysqli_stmt_execute($stmt_sec);
        $res_sec = mysqli_stmt_get_result($stmt_sec);
        $cargo_data = mysqli_fetch_assoc($res_sec);
    }


    if (true) {
        // Redirige de nuevo al listado
        header("Location: ./secretario-usuario.php");
        exit;
    } else {
        echo "Error en la inserción: " . mysqli_error($conn);
    }*/


?>

Agregar-usuario.php:
<?php
include('..\..\conexion.php');
$conn = conectar_bd();


$id_usuario = $_POST['id_usuario'] ?? null;
$ci_usuario = $_POST['ci_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$gmail_usuario = $_POST['gmail_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];
$cargo_usuario = $_POST['cargo_usuario'];
$contrasenia_usuario = $_POST['contrasenia_usuario'];




    // Hashear la contraseña
    $hashed_password = password_hash($contrasenia_usuario, PASSWORD_BCRYPT);


    $sql = "INSERT INTO usuario
      (ci_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, contrasenia_usuario)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die("Error en prepare: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "sssssss", $ci_usuario, $nombre_usuario, $apellido_usuario, $gmail_usuario, $telefono_usuario, $cargo_usuario, $hashed_password);
    $success = mysqli_stmt_execute($stmt);
    $idUsuario = $conn->insert_id;




    if ($cargo_usuario === "Docente") {
        $sql_docente = "INSERT INTO docente (id_usuario)
                    VALUES (?);";
        $stmt_doc = mysqli_prepare($conn, $sql_docente);
        mysqli_stmt_bind_param($stmt_doc, "i", $idUsuario);
        mysqli_stmt_execute($stmt_doc);


    } elseif ($cargo_usuario === "Adscripto") {
        $sql_adscripto = "INSERT INTO adscripto (id_usuario)
                      VALUES (?)";
        $stmt_ads = mysqli_prepare($conn, $sql_adscripto);


        if (!$stmt_ads) {
            die("Error prepare adscripto: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_ads, "i", $idUsuario);
        mysqli_stmt_execute($stmt_ads);
    } elseif ($cargo_usuario === "Secretario") {
        $sql_secretario = "INSERT INTO secretario (id_usuario)
                       VALUES (?)";
        $stmt_sec = mysqli_prepare($conn, $sql_secretario);
        mysqli_stmt_bind_param($stmt_sec, "i", $idUsuario);
        mysqli_stmt_execute($stmt_sec);
    }


    if ($success) {
        // Redirige de nuevo al listado
        header("Location: ./secretario-usuario.php");
        exit;
    } else {
        echo "Error en la inserción: " . mysqli_error($conn);
    }





