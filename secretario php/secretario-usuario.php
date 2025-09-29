<?php 
include('C:\xampp\htdocs\C-C2025\connection.php');
$conn = connection();
$sql = "SELECT * FROM usuario";
$query = mysqli_query($conn, $sql); //mysqli_query FUNCIÓN de php para EJECUTAR SQL
/*Esta variable llamada query lo que hace es contener info. de la conección (si está conectada o no a la BD) y a la CONSULTA que se necesita hacerl.*/
$message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida secretario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
   <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <a href="../index.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cerrar Sesión</a>

         <a href="secretario-usuario.php" class="fw-semibold seleccionado mb-2">Usuarios</a>
        <a href="secretario-horario.php" class="nav-opciones">Horarios</a>
        <a href="secretario-grupo.php" class="nav-opciones">Grupos</a>
      </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
       <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../index.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../index.php">Cerrar Sesión</a>
          </div>
        </div>

     <a href="secretario-usuario.php" class="fw-semibold seleccionado mb-2">Usuarios</a>
        <a href="secretario-horario.php" class="nav-opciones">Horarios</a>
        <a href="secretario-grupo.php" class="nav-opciones">Grupos</a>
       </div>


<!-- Contenido principal -->
<main class="col-md-9 principal-estudiantes" >


    <img src="../img/logo.png" alt="Logo" class="logo"> 

    
    <div class="bloque-agregar">
    <button class="etiqueta">Usuarios</button>
    <a href="./secretario_CRUD.php"><button class="agregar">+</button></a>
  </div>

    <table class="tabla-reserva">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Telefono</th>
          <th>Cedula</th>
          <th>Cargo</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
            while($row = mysqli_fetch_array($query)):
            ?>
       <tr>
          <td><?= $row['nombre_usuario']  ?></td>
          <td><?= $row['apellido_usuario']  ?></td>
          <td><?= $row['gmail_usuario']  ?></td>
          <td><?= $row['telefono_usuario']  ?></td>
          <td><?= $row['ci_usuario']  ?></td>
          <td><?= $row['cargo_usuario']  ?></td>
          <td><a href="./update_user_secretario.php?id_usuario=<?= $row['id_usuario'] ?>"<i class="bi bi-pencil"></i></a></td>
          <td><a href="./delete_user_secretario.php?id_usuario=<?= $row['id_usuario']  ?>"><i class="bi bi-trash"></a></i></td>
        </tr>
        <?php 
            endwhile;
            ?>
      </tbody>
    </table>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../form_logIn.js"></script>
</body>
</html>