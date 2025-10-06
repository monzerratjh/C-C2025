<?php 

/* 
// Obtener el nombre del usuario de la sesión para personalizar el mensaje
$nombre_secretario = $_SESSION['nombre_usuario'] ?? 'Secretario/a';
?>
    <h1>Bienvenido, <?php echo htmlspecialchars($nombre_secretario); ?></h1>

*/

include('../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenida secretario</title>
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="/img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="/img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
   <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
      <div class="offcanvas-body d-flex flex-column">
        <div>
        <a href="/utils/log-out.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="sessionClose">Cerrar Sesión</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>  
        <a href="" class="nav-opciones">Usuarios</a>
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
            <a href="/utils/log-out.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="/utils/log-out.php">Cerrar Sesión</a>
          </div>
           <i class="bi bi-translate traductor-menu"></i>
        </div>

        <a href="" class="nav-opciones">Usuarios</a>
        <a href="secretario-horario.php" class="nav-opciones">Horarios</a>
        <a href="/secretario/grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
       </div>


<!-- Contenido principal -->
<main class="col-md-9 principal" >

    <img src="/img/logo.png" alt="Logo" class="logo"> 
    
    <h1 class="bienvenida">Bienvenid@</h1>
    <h1 class="bienvenida">Secretari@ @username</h1>
  
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>