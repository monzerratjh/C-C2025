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
      <div>
        <a href="/utils/log-out.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>Cerrar sesión
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="/users/secretario/usuario/secretario-usuario.php" class="nav-opciones mb-2">Usuarios</a>
      <a href="/users/secretario/horario/horario-secretario.php" class="nav-opciones mb-2">Horarios</a>
      <a href="/users/secretario/grupo/secretario-grupo.php" class="nav-opciones mb-2">Grupos</a>
    </div>
  </div>

  <!-- Contenedor principal con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../../utils/log-out.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="../../utils/log-out.php">Cerrar sesión</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="/users/secretario/usuario/secretario-usuario.php" class="nav-opciones">Usuarios</a>
      <a href="/users/secretario/horario/horario-secretario.php" class="nav-opciones">Horarios</a>
      <a href="/users/secretario/grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../img/logo.png" alt="Logo" class="logo">
      <h1 class="bienvenida">Bienvenid@  <br> <?php echo htmlspecialchars($nombre_secretario). ' ' . htmlspecialchars($apellido_secretario). '!'; ?></h1>
    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
