<?php
include('./../../conexion.php');
session_start();

// Obtener el nombre del usuario de la sesión para personalizar el mensaje
$nombre_secretario = $_SESSION['nombre_usuario'] ?? 'usuario/a';
$apellido_secretario = $_SESSION['apellido_usuario'] ?? 'usuario/a';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenida Secretario</title>

  <!-- Bootstrap CSS + Iconos + letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- CSS propio -->
  <link rel="stylesheet" href="./../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="./../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        
        <a href="#" class="btn-cerrar-sesion mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>Cerrar sesión
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./usuario/secretario-usuario.php" class="nav-opciones mb-2">Usuarios</a>
      <a href="./horario/horario-secretario.php" class="nav-opciones mb-2">Horarios</a>
      <a href="./grupo/secretario-grupo.php" class="nav-opciones mb-2">Grupos</a>
      <a href="./recurso/secretario-recurso.php" class="nav-opciones">Recursos</a>
    </div>
  </div>

  <!-- Contenedor principal con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="#" class="btn-cerrar-sesion">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="#" class="btn-cerrar-sesion">Cerrar sesión</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
      <a href="./horario/horario-secretario.php" class="nav-opciones">Horarios</a>
      <a href="./grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
      <a href="./recurso/secretario-recurso.php" class="nav-opciones">Recursos</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../img/logo.png" alt="Logo" class="logo">
      <h1 class="bienvenida"> <span data-i18n="welcom3">Bienvenid@</span> <br> <?php echo htmlspecialchars($nombre_secretario). ' ' . htmlspecialchars($apellido_secretario). '!'; ?></h1>
    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="./../../utils/form-log-in.js"></script> 
  
  <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../utils/translate.js"></script>
</body>
</html>
