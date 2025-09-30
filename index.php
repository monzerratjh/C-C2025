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
    <i class="bi bi-translate traductor-index"></i>  
    </div>

    <div>
    <img src="img/logo.png" alt="Logo">
    </div>
 </header>


 <nav class="bienvenida-index">
    <h1>¡Bienvenido!</h1>
    <p>¿Quién eres?</p>
 </nav>

  <main class="opciones">
    
  
    <a href="./estudiante/estudiante.php">
      <section class="opcion estudiante">
        <img src="img/estudiante.png" alt="Estudiante">
        <span>Estudiante</span>
      </section>
    </a>

    <a href="./adscripto/adscripto-log.php">
      <section class="opcion adscripto">
        <img src="img/adscripto.png" alt="Adscripto">
        <span>Adscripto</span>
      </section>
    </a>

    <a href="./docente/docente-log.php">
      <section class="opcion docente">
        <img src="img/profesor.png" alt="Profesor">
        <span>Docente</span>
      </section>
    </a>

    <a href="./secretario/secretario-log.php">
    <section class="opcion secretaria">
      <img src="img/secretario.png" alt="Secretario">
      <span>Secretario</span>
    </section>
    </a>

  </main>

</body>
</html>
