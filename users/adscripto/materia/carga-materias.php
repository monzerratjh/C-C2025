<?php 
include('../../../conexion.php');
$conn = conectar_bd();
$sql = "SELECT * FROM asignatura";
$query = mysqli_query($conn, $sql); //mysqli_query FUNCIÓN de php para EJECUTAR SQL
/*Esta variable llamada query lo que hace es contener info. de la conexión (si está conectada o no a la BD) y a la CONSULTA que se necesita hacerl.*/

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
        <h2>Cargar materias</h2>
        <p>Ingrese la materia.</p>

        <div class="busqueda">
          <form action="./cargar-materias-ada.php" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" id="insertar-materia" name="insertar-materia" aria-describedby="" placeholder="Ej: Programación Full-Stack" required>
                <small id="emailHelp" class="form-text text-muted">Asegúrese de que quede bien escrito.</small>
            </div>
            <button type="submit" class="btn btn-primary">Cargar</button>
            </form>
            <br>
        </div>

        <table class="table">
            <br>
           <h2>Materias cargadas</h2>
            <thead>
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Nombre Materia</th>
                    <!--<th scope="col">Profesor</th>
                    <th scope="col">Aula</th>-->
                    <th>
                </tr>
            </thead>
            <tbody>
                 <?php
                    while($row = mysqli_fetch_array($query)):
                ?>
                <tr>
                    <th scope="row"><?= $row['id_asignatura']  ?></th>
                    <td><?= $row['nombre_asignatura']  ?></td>
                    <td><a data-bs-toggle="modal"
                            data-bs-target="#update_modal<?= $row['id_asignatura'] ?>"><i class="bi bi-pencil"></i></a></td>
                    <td><a href="delete_materia.php?id_asignatura=<?= $row['id_asignatura']  ?>"><i class="bi bi-trash"></a></i></td>
                </tr>
                <!-- Modal para actualizar-->
                 
                    <div class="modal fade" id="update_modal<?= $row['id_asignatura'] ?>" tabindex="-1">  
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitulo">Edición de Usuarios</h5>
                                </div>
                                <form method="POST" action="./editar_materia.php" id="editarMateria">
                                    <div class="modal-body" id="update-modal<?= $row['id_asignatura'] ?>">
                                        <!-- Campos ocultos -->
                                        <input type="hidden" name="id_asignatura" value="<?= $row['id_asignatura'] ?>">

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="editar-materia" name="editar-materia" aria-describedby="" placeholder="Ej: Programación Full-Stack" value="<?= htmlspecialchars($row['nombre_asignatura']) ?>">
                                            <small id="emailHelp" class="form-text text-muted">Asegúrese de que quede bien escrito.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Cargar cambios</button> 
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
      </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/redireccionar-grupo.js"></script>
<!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>

  <script src="/utils/translate.js"></script>
</body>
</html>