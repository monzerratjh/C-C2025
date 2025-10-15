<?php 
include('../../conexion.php');
//include('../../encabezado.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aviso de Falta</title>
  <!-- Bootstrap CSS + Iconos + letras-->
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
        <a href="docente-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>Volver
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>
      <a href="docente-grupo.php" class="nav-opciones mb-2">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="nav-opciones mb-2">Reservar Espacio</a>
      <a href="docente-falta.php" class="fw-semibold seleccionado mb-2">Avisar Falta</a>
    </div>
  </div>

  <!-- Contenedor general con GRID -->
  <div class="contenedor">

    <!-- Barra lateral -->
    <aside class="barra-lateral">
      <div class="volverGeneral">
        <div class="volver">
          <a href="docente-bienvenida.php">
            <i class="bi bi-arrow-left-circle-fill icono-volver"></i>
          </a>
          <a href="docente-bienvenida.php">Volver</a>
        </div>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="docente-grupo.php" class="nav-opciones mb-2">Grupos a Cargo</a>
      <a href="docente-reservar.php" class="nav-opciones mb-2">Reservar Espacio</a>
      <a href="docente-falta.php" class="fw-semibold seleccionado">Avisar Falta</a>
    </aside>

    <!-- Contenido principal -->
    <main class="principal">
      <img src="./../../img/logo.png" alt="Logo" class="logo">
      
      <div class="bloque-aviso">
        <button class="etiqueta" data-bs-toggle="modal" data-bs-target="#modalFalta">Avisar Falta</button>
      </div>

      <!-- Modal para enviar aviso de falta -->
      <div class="modal fade" id="modalFalta" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">Avisar Falta</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Formulario -->
            <form method="POST" id="formFalta">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="materia">Materia</label>
                  <input type="text" class="form-control" id="materia" name="materia" required>
                </div>

                <div class="mb-3">
                  <label for="grupo">Grupo al que falta</label>
                  <input type="text" class="form-control" id="grupo" name="grupo" required>
                </div>

                <div class="mb-3">
                  <label for="fecha">Fecha</label>
                  <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>

                <div class="mb-3">
                  <label for="horas">Cantidad de horas</label>
                  <input type="number" class="form-control" id="horas" name="horas" required>
                </div>

                <div class="mb-3">
                  <label for="motivo">Motivo</label>
                  <textarea class="form-control" id="motivo" name="motivo" required></textarea>
                </div>
              </div>

              <!-- Formulario -->
              <form method="POST" id="formFalta">
                   <div class="modal-body">
                  <div class="mb-3">
                    <label for="materia">Materia</label>
                      <input type="text" class="form-control" id="materia-falta" name="materia-falta" required>
                  </div>

                  <div class="mb-3">
                    <label for="grupo">Grupo al que falta</label>
                      <input type="text" class="form-control" id="grupo-a-faltar" name="grupo-a-faltar" required>
                  </div>

                  <div class="mb-3">
                    <label for="fecha">Fecha</label>
                      <input type="date" class="form-control" id="fecha-a-faltar" name="fecha-a-faltar" required>
                  </div>

                  <div class="mb-3">
                    <label for="horas">Cantidad de horas</label>
                     <input type="number" class="form-control" id="horas-faltadas" name="horas-faltadas" required>
                  </div>

                  <div class="mb-3">
                    <label for="motivo">Motivo</label>
                      <textarea class="form-control" id="motivo-falta" name="motivo-falta" required></textarea>
                  </div>

                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Avisar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

  </div> <!-- termina contenedor -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
