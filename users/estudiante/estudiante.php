<?php 
include('./../../conexion.php');
$conn = conectar_bd();

// Obtener todos los grupos con su id y nombre
$sqlGrupos = "SELECT id_grupo, nombre_grupo FROM grupo";
$resultGrupos = mysqli_query($conn, $sqlGrupos);
$grupos = [];

    // fila           // array asociativo
while ($row = mysqli_fetch_assoc($resultGrupos)) {
    $grupos[] = $row; // cada *FILA tiene ['id_grupo', 'nombre_grupo']
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel estudiante</title>

  <!-- Bootstrap CSS + Iconos + letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- CSS propio -->
  <link rel="stylesheet" href="./../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none"> <!-- Oculta el nav en pantallas medianas hacia arriba -->
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"> <!-- Se abre el menu tipo offcanvas (panel lateral) -->
        <img class="menuResponsive" src="./../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="./../../img/logo.png" alt="logoResponsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral"> <!-- off-canvas-start hace qeu el menu se abra desde la izquierda y -1 hace que el menu sea enfocable-->
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="banner-parte-superior">
        <a href="../../index.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i><span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../estudiante/estudiante.php" class="fw-semibold seleccionado mb-2" data-i18n="student">Estudiante</a>
      <a href="./../adscripto/adscripto-log.php" class="nav-opciones mb-2" data-i18n="adscripto">Adscripto</a>
      <a href="./../docente/docente-log.php" class="nav-opciones mb-2" data-i18n="teacher">Docente</a>
      <a href="./../secretario/secretario-log.php" class="nav-opciones mb-2" data-i18n="secretary">Secretario</a>
    </div>
  </div>

  <!--Contenedor principal con GRID -->
  <div class="contenedor">

   <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="../../index.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="../../index.php" data-i18n="goBack">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu" title="Traducir"></i>
      </div>

      <a href="../estudiante/estudiante.php" class="fw-semibold seleccionado" data-i18n="student">Estudiante</a>
      <a href="../adscripto/adscripto-log.php" class="nav-opciones" data-i18n="adscripto">Adscripto</a>
      <a href="../docente/docente-log.php" class="nav-opciones" data-i18n="teacher">Docente</a>
      <a href="../secretario/secretario-log.php" class="nav-opciones" data-i18n="secretary">Secretario</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../img/logo.png" alt="Logo" class="logo"> 
     <h2 data-i18n="student">ESTUDIANTE</h2>
      <p data-i18n="enterGroup">Ingresa tu grupo correspondiente</p>

      <div class="busqueda">
        <i class="bi bi-search icono-busqueda"></i>
        <input type="text" class="diseno-busqueda" data-i18n-placeholder="enterGroupPlaceholder" placeholder="Ingrese su grupo" list="lista-grupos" id="grupoInput" />
          <datalist id="lista-grupos">
          <?php foreach ($grupos as $g): ?>
            <option value="<?php echo htmlspecialchars($g['nombre_grupo'], ENT_QUOTES, 'UTF-8'); ?>" 
                    data-id="<?php echo $g['id_grupo']; ?>">
            </option>
          <?php endforeach; ?>
        </datalist> 
      </div>
    </main>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/redireccionar-grupo.js"></script>

  <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../utils/translate.js"></script>

</body>
</html>
