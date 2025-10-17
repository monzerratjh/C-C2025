<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Página en Mantenimiento</title>
   <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS propio -->
  <link rel="stylesheet" href="./../../../css/style.css">   
</head>
<body>


  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="./../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>


  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
       <a href="./../secretario-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i><span data-i18n="goBack">Volver</span>
        </a> 
      </div>
        <a href="../usuario/secretario-usuario.php" class="nav-opciones" data-i18n="users">Usuarios</a>
        <a href="../horario/horario-secretario" class="nav-opciones" data-i18n="schedule">Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="nav-opciones" data-i18n="groups">Grupos</a>
        <a href="./../recurso/secretario-recurso.php" class="fw-semibold seleccionado mb-2" data-i18n="resources">Recursos</a>
   
        </div>
  </div>


  <!-- Contenedor general -->
  <div class="contenedor">  

    <!-- Banner pantallas grandes -->
    <aside class="barra-lateral d-none d-md-flex">
      <div class="volverGeneral">
        <div class="volver">
        <a href="../secretario-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
        <a href="../secretario-bienvenida.php" data-i18n="goBack">Volver</a>
      </div>
      <i class="bi bi-translate traductor-menu"></i>
      </div>


      <a href="../usuario/secretario-usuario.php" class="nav-opciones" data-i18n="users">Usuarios</a>
      <a href="../horario/horario-secretario.php" class="nav-opciones" data-i18n="schedule">Horarios</a>
      <a href="../grupo/secretario-grupo.php" class="nav-opciones" data-i18n="groups">Grupos</a>
      <a href="./../recurso/secretario-recurso.php" class="fw-semibold seleccionado mb-2" data-i18n="resources">Recursos</a>
   
    </aside>

    <!-- Contenido principal-->
    <main class="principal"> 
      <img src="./../../../img/logo.png" alt="Logo" class="logo">

  <div class="loader"></div>
  <h1 data-i18n="underMaintenance">Estamos en mantenimiento</h1>
  <p data-i18n="backSoon">Volveremos pronto con algo mejor ✨</p>

      </main>
      <!-- Bootstrap JS + Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



    <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>


</body>
</html>
