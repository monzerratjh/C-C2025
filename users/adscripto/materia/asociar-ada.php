<?php 
include('../../../conexion.php');
$conn = conectar_bd();
// Consultas para llenar selects
$query_asig = mysqli_query($conn, "SELECT * FROM asignatura");
$query_doc = mysqli_query($conn, "SELECT d.id_docente, u.nombre_usuario, u.apellido_usuario
                                  FROM docente d
                                  INNER JOIN usuario u ON d.id_usuario = u.id_usuario");
$query_esp = mysqli_query($conn, "SELECT * FROM espacio");
$query_grupo = mysqli_query($conn, "SELECT * FROM grupo");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Adscriptos</title>
    
    <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS propio -->
    <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../../../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
 <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div>
        <a href="../adscripto-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./carga-materias.php" class="fw-semibold seleccionado mb-2" data-i18n="addSubjects">Cargar Horario</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../adscripto-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../adscripto-bienvenida.php" data-i18n="goBack">Volver</a>
          </div>
            <i class="bi bi-translate traductor-menu"></i>
        </div>

      <a href="../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./carga-materias.php" class="fw-semibold seleccionado mb-2" data-i18n="addSubjects">Cargar Materias</a>
    </div>


<!-- Contenido principal -->
      <div class="col-md-9 col-12 principal">
        <img src="../../../img/logo.png" alt="Logo" class="logo"> 
        <h2>Asignar aula, docente y materia a grupo.</h2>
        <p>Ingrese los datos.</p>

        <div class="busqueda">
            
          <form action="./carga-materias-gada.php" method="POST">
            <div class="form-group">
                <select class="form-control" id="cargar-materia" name="cargar-materia" aria-describedby="" required>
                    <option value="">Seleccionar materia</option>
                
                     <?php
                        $asig = mysqli_query($conn, "SELECT * FROM asignatura");
                        while ($a = mysqli_fetch_assoc($asig)) {
                         echo "<option value='{$a['id_asignatura']}'>{$a['nombre_asignatura']}</option>";
                        }
                    ?>

                </select>
                <br>
                <!-- Selección de docente -->
                <select class="form-control" name="id_docente" required>
                    <option value="">Seleccionar docente</option>
                    <?php
                      $doc = mysqli_query($conn, "
                        SELECT d.id_docente, u.nombre_usuario, u.apellido_usuario
                        FROM docente d
                        INNER JOIN usuario u ON d.id_usuario = u.id_usuario
                      ");
                        while ($d = mysqli_fetch_assoc($doc)) {
                          echo "<option value='{$d['id_docente']}'>{$d['nombre_usuario']} {$d['apellido_usuario']}</option>";
                        }
                      ?>
                </select>
                <br>

                <!-- Selección de espacio -->
                <select class="form-control" name="id_espacio" required>
                    <option value="">Seleccionar espacio</option>
                    <?php
                    $esp = mysqli_query($conn, "SELECT * FROM espacio");
                    while ($e = mysqli_fetch_assoc($esp)) {
                        echo "<option value='{$e['id_espacio']}'>{$e['nombre_espacio']}</option>";
                    }
                    ?>
                </select>
                <br>
                <!-- Selección de grupo -->
                <select class="form-control" name="id_grupo" required>
                  <option value="">Seleccionar grupo</option>
                    <?php
                      $grupos = mysqli_query($conn, "SELECT * FROM grupo");
                      while ($g = mysqli_fetch_assoc($grupos)) {
                      echo "<option value='{$g['id_grupo']}'>{$g['nombre_grupo']}</option>";
                      }
                    ?>
                </select>
                <br>

                <button type="submit" class="btn btn-primary">Asignar</button>
            </form>
        </div>

        <table class="table">
            <br> <br>
           <h2>Materias cargadas</h2>
            <thead>
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Nombre Materia</th>
                    <th scope="col">Profesor</th>
                    <th scope="col">Aula</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                </tr>
            </tbody>
        </table>
      </div>

    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/redireccionar-grupo.js"></script>
<!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>

  <script src="/utils/translate.js"></script>
</body>
</html>