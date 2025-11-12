<?php
include('../../../conexion.php');
//include('../../../encabezado.php');


// Inicia la sesión para poder acceder a las variables globales $_SESSION
session_start();


// Obtiene el ID del secretario guardado en la sesión (cuando el usuario inició sesión)
// Si no existe (por ejemplo, si el usuario no es secretario o la sesión expiró),
// se asigna el valor null para evitar errores.
$id_secretario = $_SESSION['id_secretario'] ?? null;


$con = conectar_bd();


// Consulta SQL para obtener todos los grupos junto con el adscripto asociado y el nombre del usuario adscripto
$result = $con->query("
    SELECT grupo.id_grupo,
           grupo.nombre_grupo,
           grupo.orientacion_grupo,
           grupo.turno_grupo,
           grupo.cantidad_alumno_grupo,
           adscripto.id_adscripto,
           adscripto.id_usuario AS id_usuario_adscripto,
           usuario.nombre_usuario,
           usuario.apellido_usuario
    FROM grupo
    JOIN adscripto
        ON grupo.id_adscripto = adscripto.id_adscripto
    JOIN usuario
        ON adscripto.id_usuario = usuario.id_usuario
    ORDER BY grupo.nombre_grupo ASC
");


// Obtener nombre y apellido adscripto mediante id (usuario)
$adscriptoResult = $con->query("SELECT adscripto.id_adscripto, usuario.nombre_usuario, usuario.apellido_usuario
                                                         FROM adscripto
                                                         JOIN usuario ON adscripto.id_usuario=usuario.id_usuario");



$ENUMturno = $con->query("SHOW COLUMNS FROM grupo LIKE 'turno_grupo'");
$rowTurno = $ENUMturno->fetch_assoc();

// Extraer los valores del ENUM de turnos
preg_match_all("/'([^']+)'/", $rowTurno['Type'], $turnoCoincidencias);
$turnos = $turnoCoincidencias[1];


// Obtener las opciones del ENUM "orientacion_grupo" directamente desde la BD
$ENUMresult = $con->query("SHOW COLUMNS FROM grupo LIKE 'orientacion_grupo'");
$rowENUM = $ENUMresult->fetch_assoc();


// Extraer los valores entre comillas simples del tipo ENUM
preg_match_all("/'([^']+)'/", $rowENUM['Type'], $coincidencias);
$orientaciones = $coincidencias[1]; /* Array con todas las orientaciones válidas


 * preg_match_all -> match=coincidencia
 
$coincidencias[0] = ["'Tecnologías de la Información'", "'Diseño Gráfico'", "'Secretariado'"];
$coincidencias[1] = ["Tecnologías de la Información", "Diseño Gráfico", "Secretariado"];




fetch_assoc() funcion q toma una fila del resultado de una consulta SQL y la devuelve como un array asociativo.


[
  "id_usuario" => 1,
  "nombre" => "Ana",
  "apellido" => "Pérez"
]




  SQL


 adscripto.id_usuario AS id_usuario_adscripto
  * Columna de adscripto renombrada para diferenciarla de usuario.id_usuario.


 usuario.nombre_usuario, usuario.apellido_usuario
  * asociados al adscripto.


JOIN adscripto ON grupo.id_adscripto = adscripto.id_adscripto
  * Cada grupo se asocia con su adscripto.


JOIN usuario ON adscripto.id_usuario = usuario.id_usuario
  * Cada adscripto se asocia con su usuario.
*/


$con->close(); // cierro conexión cuando ya tengo todos los datos
?>


<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Secretario</title>
  <!-- Bootstrap CSS + Iconos + letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./../../../css/style.css">
</head>
<body class="grupo-user-page">


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
        <i class="bi bi-translate traductor-menu"></i>
      </div>
        <a href="../usuario/secretario-usuario.php" class="nav-opciones" data-i18n="users">Usuarios</a>
        <a href="./../horario/horario-secretario.php" class="nav-opciones" data-i18n="schedule" >Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="fw-semibold seleccionado mb-2" data-i18n="groups">Grupos</a>
        <a href="./../recurso/secretario-recurso.php" class="nav-opciones" data-i18n="resources">Recursos</a>
   
        </div>

        <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
      
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
        <a href="./../horario/horario-secretario.php" class="nav-opciones" data-i18n="schedule" >Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="fw-semibold seleccionado mb-2" data-i18n="groups">Grupos</a>
        <a href="./../recurso/secretario-recurso.php" class="nav-opciones" data-i18n="resources">Recursos</a>
   
      <!-- BOTÓN CERRAR SESIÓN -->
   <a href="#" class="btn-cerrar-sesion-bajo btn-cerrar-sesion mb-3">
    <i class="bi bi-box-arrow-right me-2"></i>
    <span data-i18n="sessionClose">Cerrar sesión</span>
  </a>
      
    </aside>


    <!-- Contenido principal-->
    <main class="principal"> 
      <img src="./../../../img/logo.png" alt="Logo" class="logo">


      <div class="acordion-total">
  <div class="acordion">
  
              <h2 data-i18n="groups">Grupos</h2>
              <p data-i18n="manageGroups">Gestioná los grupos: agregá, modificá o eliminá según sea necesario.</p> 

              <button class="boton-opciones2 agregar colorfondorosa"
                      data-bs-toggle="modal"
                      data-bs-target="#modalGrupo"
                      onclick="document.getElementById('accion').value='insertar';">
                 <h4>+</h4></button>

<?php while($row = $result->fetch_assoc()):?>
<div class="dia">
    <button class="boton-opciones miercoles">
       <?php echo htmlspecialchars($row['nombre_grupo'], ENT_SUBSTITUTE); ?>
    </button>
    <div class="contenido-dia grupos-usuarios-responsive">
<!-- Tabla para pantallas pequeñas -->
    <table class="tabla-grupos-usuarios-responsive ">
        <tr><td><b data-i18n="orientation">Orientación:</b> <?php echo htmlspecialchars($row['orientacion_grupo']); ?></td></tr>
        <tr><td><b data-i18n="shift">Turno:</b> <?php echo htmlspecialchars($row['turno_grupo']); ?></td></tr>
        <tr><td><b data-i18n="numberOfStudents">Cantidad de alumnos:</b> <?php echo $row['cantidad_alumno_grupo']; ?></td></tr>
        <tr><td><b data-i18n="adscripto">Adscripto:</b> <?php echo htmlspecialchars($row['nombre_usuario'].' '.$row['apellido_usuario']); ?></td></tr>
        <tr class="editar">
          <th class="grupos-usuarios-responsive"> <i class="btn btn-sm bi bi-pencil-square"
                              data-bs-toggle="modal"
                              data-bs-target="#modalGrupo"
                              onclick="cargarEditar(
                                  '<?php echo $row['id_grupo']; ?>',
                                  '<?php echo $row['orientacion_grupo']; ?>',
                                  '<?php echo $row['turno_grupo']; ?>',
                                  '<?php echo $row['nombre_grupo']; ?>',
                                  '<?php echo $row['cantidad_alumno_grupo']; ?>',
                                  '<?php echo $row['id_adscripto']; ?>' // muestra los datos que estan guardados (y ahora seran editados)
                              )">
                      </i>
            </th>
        </tr>
        <tr>
            <th class="grupos-usuarios-responsive"><form id="formEliminar<?php echo $row['id_grupo'];?>"
                                    method="POST"
                                    style="display:inline;">
                                  <input type="hidden" name="accion" value="eliminar">
                                  <input type="hidden" name="id_grupo" value="<?php echo $row['id_grupo']; ?>">
                                
                                  <i
                                          class="btn btn-sm eliminar-grupo-btn bi bi-trash "
                                          data-id="<?php echo $row['id_grupo']; ?>">
                                </i>
                        </form>
            </th>            
        </tr>
      </div>
    </table>

    <!-- Tabla para pantallas grandes -->
<table class="tabla-secretario">
        <thead>
          <tr>
            <th data-i18n="orientation">Orientación</th>
            <th data-i18n="shift">Turno</th>
            <th data-i18n="numberOfStudents">Cantidad de alumnos</th>
            <th data-i18n="adscripto">Adscripto</th>
            <th> <i class="btn btn-sm bi bi-pencil-square"
                    data-bs-toggle="modal"
                    data-bs-target="#modalGrupo"
                    onclick="cargarEditar(
                        '<?php echo $row['id_grupo']; ?>',
                        '<?php echo $row['orientacion_grupo']; ?>',
                        '<?php echo $row['turno_grupo']; ?>',
                        '<?php echo $row['nombre_grupo']; ?>',
                        '<?php echo $row['cantidad_alumno_grupo']; ?>',
                        '<?php echo $row['id_adscripto']; ?>' // muestra los datos que estan guardados (y ahora seran editados)
                    )">
            </i></th>
            <th><form id="formEliminar<?php echo $row['id_grupo'];?>"
                        method="POST"
                        style="display:inline;">
                      <input type="hidden" name="accion" value="eliminar">
                      <input type="hidden" name="id_grupo" value="<?php echo $row['id_grupo']; ?>">
                     
                      <i
                              class="btn btn-sm eliminar-grupo-btn bi bi-trash "
                              data-id="<?php echo $row['id_grupo']; ?>">
                    </i>
            </form>
            </th>
          </tr>
          
        </thead>
        <tbody>
            <tr>
           
            <td>
             <?php echo htmlspecialchars($row['orientacion_grupo'], ENT_SUBSTITUTE); //se usa para eliminar etiquetas HTML y PHP de un string. ?>
            </td>
            <td>
              <?php echo $row['turno_grupo']; ?>
            </td>
            <td><?php echo $row['cantidad_alumno_grupo']; ?></td>
            <td><?php echo htmlspecialchars($row['nombre_usuario'], ENT_SUBSTITUTE).' '. htmlspecialchars($row['apellido_usuario'], ENT_SUBSTITUTE); ?></td>
          </tr>
          
        </tbody>
      </table>

    </div>
</div>

<?php endwhile;?>


            <!-- Modal para insertar o editar grupo -->
            <div class="modal fade" id="modalGrupo" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" data-i18n="groupManagement">Gestión de Grupos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>


                  <form method="POST" id="formGrupo">
                    <div class="modal-body">


                      <input type="hidden" id="accion" name="accion">
                      <input type="hidden" id="id_grupo" name="id_grupo">


                      <div class="mb-3">
                        <label data-i18n="groupName">Nombre del grupo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: 3ro MD"  data-i18n-placeholder="egMC">
                      </div>


                      <div class="mb-3">
                        <label for="orientacion" data-i18n="orientation">Orientación</label>
                        <input type="text" name="orientacion" class="form-control"
                          data-i18n-placeholder="enterOrientation"  placeholder="Ingrese la orientación" list="orientaciones" id="orientacionInput" required />
                        <datalist id="orientaciones">
                          <?php foreach($orientaciones as $o): ?>
                            <option value="<?php echo htmlspecialchars($o, ENT_QUOTES, 'UTF-8'); ?>"></option>
                          <?php endforeach; ?>
                        </datalist>
                      </div>


                      <div class="mb-3">
                        <label data-i18n="shift" >Turno</label>
                        <select class="form-control" id="turno" name="turno" required>
                          <option value="" data-i18n="select">Seleccione...</option>
                          <?php foreach($turnos as $t): ?>
                              <option value="<?php echo htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); ?>">
                                  <?php echo htmlspecialchars($t, ENT_SUBSTITUTE, 'UTF-8'); ?>
                              </option>
                          <?php endforeach; ?>
                        </select>
                      </div>


                      <div class="mb-3">
                        <label data-i18n="numberStudents">Cantidad de alumnos</label>
                        <input type="number" data-i18n-placeholder="eg34" class="form-control" id="cantidad" name="cantidad" placeholder="Ej: 30"required>
                      </div>


                      <div class="mb-3">
                        <label data-i18n="adscripto">Adscripto</label>
                        <select class="form-control" name="id_adscripto" required>
                        <?php
                        while($adscriptoRow = $adscriptoResult->fetch_assoc()){
                            echo "<option value='".$adscriptoRow['id_adscripto']."'>".$adscriptoRow['nombre_usuario']." ".$adscriptoRow['apellido_usuario']."</option>";
                        }
                        ?>
                        </select>
                      </div>


                      <div class="mb-3"> <!-- id del secretario asociado con el grupo creado para que se gusrade en la bd -->
                        <input type="hidden" name="id_secretario" value="<?php echo htmlspecialchars($id_secretario); ?>">
                      </div>


                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="cancel">Cancelar</button>
                      <button type="submit" class="btn btn-primary" data-i18n="save">Guardar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>


          </div>
        </div>
      </main>
  </div> 

  <!-- Scripts -->

   <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <script src="../js/grupo.js"></script>
  <script src="./../../../utils/desplegar-acordeon.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


  <script src="../../../utils/form-log-in.js"></script> 


    <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>

</body>
</html>