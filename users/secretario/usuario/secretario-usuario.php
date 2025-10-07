<?php
include('../../../conexion.php');
$conn = conectar_bd();
$sql = "SELECT * FROM usuario";
$query = mysqli_query($conn, $sql); //mysqli_query FUNCIÓN de php para EJECUTAR SQL
/*Esta variable llamada query lo que hace es contener info. de la conección (si está conectada o no a la BD) y a la CONSULTA que se necesita hacerl.*/
$message = "";




$id_usuario = $_GET['id_usuario'] ?? null; // Si existe id_usuario, estamos editando
$usuario = null;




if ($id_usuario) {
    $sql = "SELECT * FROM usuario WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);
}




?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida secretario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>




<body>




  <!-- Menú hamburguesa para móviles -->
  <nav class="d-md-none">
    <div class="container-fluid">
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
        <img class="menuResponsive" src="/img/menu.png" alt="menu">
      </button>
      <img class="logoResponsive" src="/img/logo.png" alt="logoRespnsive">
    </div>
  </nav>




  <!-- Menú lateral (para celulares/tablets) -->
   <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <a href="../secretario-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>




        <a href="secretario-usuario.php" class="fw-semibold seleccionado mb-2">Usuarios</a>
        <a href="../horario/horario-secretario.php" class="nav-opciones">Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
      </div>
  </div>




  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">




      <!-- Banner pantallas grandes -->
       <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
          <a href="../secretario-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
          <a href="../secretario-bienvenida.php">Volver</a>
          </div>
          <i class="bi bi-translate traductor-menu"></i>
        </div>




        <a href="secretario-usuario.php" class="fw-semibold seleccionado mb-2">Usuarios</a>
        <a href="../horario/horario-secretario.php" class="nav-opciones">Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
       </div>








<!-- Contenido principal -->
<main class="col-md-9 principal-estudiantes" >








    <img src="/img/logo.png" alt="Logo" class="logo">




   
    <div class="bloque-agregar">
    <button class="etiqueta">Usuarios</button>
    <!--<a href="./secretario_CRUD.php">-->
      <button class="agregar"
              data-bs-toggle="modal"
              data-bs-target="#modalUsuario"
              onclick="document.getElementById('accion').value='insertar';"> +
      </button>
  </div>




    <table  class="tabla-horarios-secretario">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Telefono</th>
          <th>Cedula</th>
          <th>Cargo</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          while($row = mysqli_fetch_array($query)):
        ?>
       <tr>
          <td><?= $row['nombre_usuario']  ?></td>
          <td><?= $row['apellido_usuario']  ?></td>
          <td><?= $row['gmail_usuario']  ?></td>
          <td><?= $row['telefono_usuario']  ?></td>
          <td><?= $row['ci_usuario']  ?></td>
          <td><?= $row['cargo_usuario']  ?></td>
           <!--//$row['id_usuario'] -->
          <td><a class="agregar"
              data-bs-toggle="modal"
              data-bs-target="#update_modal<?= $row['id_usuario'] ?>"
              onclick="
              cargarEditar(
                '<?php echo $row['nombre_usuario']; ?>',
                '<?php echo $row['apellido_usuario']; ?>',
                '<?php echo $row['gmail_usuario']; ?>',
                '<?php echo $row['telefono_usuario']; ?>',
                '<?php echo $row['ci_usuario']; ?>',
                '<?php echo $row['cargo_usuario']; ?>',
                '<?php echo $row['id_usuario']; ?>'
                );"><i class="bi bi-pencil"></i>
          </a></td>
          <td><a href="delete_user_secretario.php?id_usuario=<?= $row['id_usuario']  ?>"><i class="bi bi-trash"></a></i></td>
        </tr>
                <!-- LRPM EDICIÓN DE MIERDA-->
    <div class="modal fade" id="update_modal<?= $row['id_usuario'] ?>" tabindex="-1">  
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitulo">Edición de Usuarios</h5>
          </div>




        <form method="POST" action="./editar-usuario.php" id="editarUsuario">
        <div class="modal-body">




        <!-- Campos ocultos para saber si es inserción o edición -->
        <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">




        <!-- Cédula -->
        <div class="mb-3">
          <label>C.I</label>
          <input type="number" name="ci_usuario" placeholder="CI" id="ci_usuario" value="<?= $row['ci_usuario']  ?>">
        </div>




        <!-- Nombre -->
        <div class="mb-3">
          <label>Nombre</label>
          <input type="text" name="nombre_usuario" placeholder="Nombres" id="nombre_usuario" value="<?= $row['nombre_usuario']  ?>">
        </div>




        <!-- Apellido -->
        <div class="mb-3">
          <label>Apellido</label>
          <input type="text" name="apellido_usuario" placeholder="Apellidos" id="apellido_usuario" value="<?= $row['apellido_usuario']  ?>">
        </div>




        <!-- Gmail -->
        <div class="mb-3">
          <label>Gmail</label>
          <input type="gmail" name="gmail_usuario" placeholder="Gmail" id="gmail_usuario" value="<?= $row['gmail_usuario']  ?>">
        </div>




        <!-- Telefono -->
        <div class="mb-3">
          <label>Teléfono</label>
          <input type="number" name="telefono_usuario" placeholder="Telefono" id="telefono_usuario" value="<?= $row['telefono_usuario']  ?>">
        </div>




        <!-- Cargo -->
        <div class="mb-3">
          <label>Cargo:</label>
          <select name="cargo_usuario" class="usuarioTipo" placeholder="Cargo">
                <option value="">Seleccionar</option>
                <option value="Docente"<?= ($row['cargo_usuario'] == 'Docente') ? 'selected' : '' ?>>Docente</option>
                <option value="Adscripto" <?= ($row['cargo_usuario'] == 'Adscripto') ? 'selected' : '' ?>>Adscripto</option>
                <option value="Secretario" <?= ($row['cargo_usuario'] == 'Secretario') ? 'selected' : '' ?>>Secretario</option>
          </select>
        </div>
       
        <!-- Contraseña -->
        <div class="mb-3">
          <label>Contraseña</label>
          <input type="password" name="contrasenia_usuario" placeholder="Contraseña" id="contrasenia_usuario" value="<?= $row['contrasenia_usuario']  ?>">
        </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <button type="submit" class="btn btn-primary">Guardar</button>
  </div>
  </form>
  </div>
  </div>
  </div>
        <?php
            endwhile;
            ?>
      </tbody>
    </table>




    <div class="modal fade" id="modalUsuario" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitulo">Creación de Usuarios</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>




        <form method="POST" action="./agregar-usuario.php" id="formUsuario">
        <div class="modal-body">




        <!-- Campos ocultos para saber si es inserción o edición -->
        <input type="hidden" id="accion" name="accion">
        <input type="hidden" id="id_usuario" >




        <!-- Cédula -->
        <div class="mb-3">
          <label>C.I</label>
          <input type="number" name="ci_usuario" placeholder="CI" id="ci_usuario" required>
        </div>




        <!-- Nombre -->
        <div class="mb-3">
          <label>Nombre</label>
          <input type="text" name="nombre_usuario" placeholder="Nombres" id="nombre_usuario" required>
        </div>




        <!-- Apellido -->
        <div class="mb-3">
          <label>Apellido</label>
          <input type="text" name="apellido_usuario" placeholder="Apellidos" id="apellido_usuario" required>
        </div>




        <!-- Gmail -->
        <div class="mb-3">
          <label>Gmail</label>
          <input type="gmail" name="gmail_usuario" placeholder="Gmail" id="gmail_usuario" required>
        </div>




        <!-- Telefono -->
        <div class="mb-3">
          <label>Teléfono</label>
          <input type="number" name="telefono_usuario" placeholder="Telefono" id="telefono_usuario" required>
        </div>




        <!-- Cargo -->
        <div class="mb-3">
          <label>Cargo:</label>
          <select name="cargo_usuario" class="usuarioTipo" id="usuarioTipo" placeholder="Cargo" required>
                <option value="">Seleccionar</option>
                <option value="Docente">Docente</option>
                <option value="Adscripto">Adscripto</option>
                <option value="Secretario">Secretario</option>
            </select>
        </div>
       
        <!-- Contraseña -->
        <div class="mb-3">
          <label>Contraseña</label>
          <input type="password" name="contrasenia_usuario" placeholder="Contraseña" id="contrasenia_usuario">
        </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <button type="submit" class="btn btn-primary">Guardar</button>
  </div>
  </form>
  </div>
  </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/validation.js"></script>
</body>
</html>
