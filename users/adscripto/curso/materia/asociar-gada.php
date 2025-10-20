<?php 
include('../../../../conexion.php');
$conn = conectar_bd();
// Consultas para llenar selects
$query_asig = mysqli_query($conn, "SELECT * FROM asignatura");
$query_doc = mysqli_query($conn, "SELECT docente.id_docente, usuario.nombre_usuario, usuario.apellido_usuario
                                  FROM docente
                                  INNER JOIN usuario ON docente.id_usuario = usuario.id_usuario");
$query_esp = mysqli_query($conn, "SELECT * FROM espacio");
$query_grupo = mysqli_query($conn, "SELECT * FROM grupo");

$query_gada = mysqli_query($conn, '                      
SELECT
	gada.*,
    asignatura.nombre_asignatura,
    grupo.nombre_grupo,
    CONCAT(usuario.nombre_usuario, " ", usuario.apellido_usuario) as nombre_completo_docente,
    espacio.nombre_espacio
FROM grupo_asignatura_docente_aula as gada, asignatura, grupo, docente, usuario, espacio
WHERE gada.id_asignatura = asignatura.id_asignatura
AND gada.id_grupo = grupo.id_grupo
AND gada.id_docente = docente.id_docente
AND gada.id_espacio = espacio.id_espacio
AND docente.id_usuario = usuario.id_usuario');
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
    <link rel="stylesheet" href="../../../../css/style.css">
</head>

<body>

  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="../../../../img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="../../../../img/logo.png" alt="logoRespnsive">
    </div>
  </nav>

  <!-- Menú lateral (para celulares/tablets) -->
 <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div>
        <a href="../adscripto-curso.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i>
          <span data-i18n="goBack">Volver</span>
        </a>
        <i class="bi bi-translate traductor-menu"></i>
      </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../../curso/adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
     </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="../adscripto-curso.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="../adscripto-curso.php" data-i18n="goBack">Volver</a>
          </div>
            <i class="bi bi-translate traductor-menu"></i>
        </div>

      <a href="./../../espacio/adscripto-espacio.php" class="nav-opciones mb-2" data-i18n="facility">Espacio</a>
      <a href="./../../reserva/reserva-adscripto.php" class="nav-opciones mb-2" data-i18n="reservation">Reserva</a>
      <a href="./../../falta/falta-docente.php" class="nav-opciones mb-2" data-i18n="teacherAbsence">Falta docente</a>
      <a href="./../../curso/adscripto-curso.php" class="fw-semibold seleccionado mb-2">Gestión de cursos</a>
    </div>


<!-- Contenido principal -->
      <div class="col-md-9 col-12 principal">
        <img src="../../../../img/logo.png" alt="Logo" class="logo"> 
        <h2>Asignar un espacio, un docente y una asignatura a grupo.</h2>
        <p>Ingrese los datos solicitados.</p>

        <div class="busqueda">
            
          <form action="./asociar-gada-accion.php" method="POST">
            <div class="form-group">
                <select class="form-control" id="cargar-materia" name="id_asignatura" aria-describedby="" required>
                    <option value="">Seleccionar asignatura</option>
                
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
                        SELECT docente.id_docente, usuario.nombre_usuario, usuario.apellido_usuario
                        FROM docente 
                        INNER JOIN usuario ON docente.id_usuario = usuario.id_usuario
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
           <h2>Asignaciones</h2> <br>
            <thead>
                <tr>
                    <th scope="col">Nombre Asignatura</th>
                    <th scope="col">Profesor</th>
                    <th scope="col">Espacio</th>
                    <th scope="col">Grupo</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_array($query_gada)): ?>
                  <tr>
                      <td><?= $row['nombre_asignatura'] ?></td>
                      
                      <td><?= $row['nombre_completo_docente'] ?></td>

                      <td><?= $row['nombre_espacio'] ?></td>
                      
                      <td><?= $row['nombre_grupo'] ?></td>

                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#update_modal<?= $row['id_gada'] ?>"><i class="bi bi-pencil"></i></a>
                      </td>
                      <td>
                        <a href="./eliminar-gada.php?id_gada=<?= $row['id_gada'] ?>"><i class="bi bi-trash"></i></a>
                      </td>
                  </tr>
                 <!-- Modal para actualizar -->
            <div class="modal fade" id="update_modal<?= $row['id_gada'] ?>" tabindex="-1">  
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Edición de asignación</h5>
                  </div>
                  <form method="POST" action="./editar-gada-accion.php" id="editarGada">
                    <div class="modal-body">
                        <input type="hidden" name="id_gada" value="<?= $row['id_gada'] ?>">
                        <div class="form-group">
                          <label>Asignatura</label>
                          <select class="form-control" name="id_asignatura" required>
                            <option value="">Seleccionar asignatura</option>
                              <?php
                                $materia_query = mysqli_query($conn, "SELECT * FROM asignatura");
                               while ($m = mysqli_fetch_assoc($materia_query)) {
                                  $selected = ($m['id_asignatura'] == $row['id_asignatura']) ? 'selected' : '';
                                  echo "<option value='{$m['id_asignatura']}' $selected>{$m['nombre_asignatura']}</option>";
                                }
                              ?>
                          </select>
                          <br>


                          <label>Docente</label>
                          <select class="form-control" name="id_docente" required>
                            <option value="">Seleccionar docente</option>
                              <?php
                                $doc = mysqli_query($conn, "
                                SELECT docente.id_docente, usuario.nombre_usuario, usuario.apellido_usuario
                                  FROM docente
                                  INNER JOIN usuario ON docente.id_usuario = usuario.id_usuario
                               ");
                                while ($d = mysqli_fetch_assoc($doc)) {
                                  $selected = ($d['id_docente'] == $row['id_docente']) ? 'selected' : '';
                                  echo "<option value='{$d['id_docente']}' $selected>{$d['nombre_usuario']} {$d['apellido_usuario']}</option>";
                                }
                              ?>
                          </select>
                          <br>


                          <label>Espacio</label>
                          <select class="form-control" name="id_espacio" required>
                            <option value="">Seleccionar espacio</option>
                              <?php
                                $esp_query = mysqli_query($conn, "SELECT * FROM espacio");
                               while ($e = mysqli_fetch_assoc($esp_query)) {
                                  $selected = ($e['id_espacio'] == $row['id_espacio']) ? 'selected' : '';
                                  echo "<option value='{$e['id_espacio']}' $selected>{$e['nombre_espacio']}</option>";
                                }
                              ?>
                          </select>
                          <br>
                         
                          <label>Grupo</label>
                          <select class="form-control" name="id_grupo" required>
                            <option value="">Seleccionar grupo</option>
                              <?php
                                $grupo_query = mysqli_query($conn, "SELECT * FROM grupo");
                               while ($g = mysqli_fetch_assoc($grupo_query)) {
                                  $selected = ($g['id_grupo'] == $row['id_grupo']) ? 'selected' : '';
                                  echo "<option value='{$g['id_grupo']}' $selected>{$g['nombre_grupo']}</option>";
                                }
                              ?>
                          </select>
                          <br>
                        </div>
                        <button type="submit" class="btn btn-primary">Cargar cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                  </form>
                  <?php endwhile; ?>
                </div>
             </div>  
            </div>
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