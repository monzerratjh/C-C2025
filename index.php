<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Pagina Principal</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  
  <header class="encabezado-index">
    <div>
      <!-- Botón de traducción -->
      <i class="bi bi-translate traductor-index" id="btn-translate"></i>  
    </div>

    <div>
      <img src="img/logo.png" alt="Logo">
    </div>
  </header>

  <nav class="bienvenida-index">
    <h1 data-i18n="welcome">¡Bienvenido!</h1>
    <p data-i18n="whoAreYou">¿Quién eres?</p>
  </nav>

  <main class="opciones">
    <a href="./estudiante/estudiante.php">
      <section class="opcion estudiante">
        <img src="img/estudiante.png" alt="Estudiante">
        <span data-i18n="student">Estudiante</span>
      </section>
    </a>

    <a href="./adscripto/adscripto-log.php">
      <section class="opcion adscripto">
        <img src="img/adscripto.png" alt="Adscripto">
        <span data-i18n="adscripto">Adscripto</span>
      </section>
    </a>

    <a href="./docente/docente-log.php">
      <section class="opcion docente">
        <img src="img/profesor.png" alt="Profesor">
        <span data-i18n="teacher">Docente</span>
      </section>
    </a>

    <a href="./secretario/secretario-log.php">
      <section class="opcion secretaria">
        <img src="img/secretario.png" alt="Secretario">
        <span data-i18n="secretary">Secretario</span>
      </section>
    </a>
  </main>

  <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="translate.js"></script>
</body>
</html>
