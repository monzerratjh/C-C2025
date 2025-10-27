<?php
include('../../../conexion.php');
$conn = conectar_bd();
$sql = "SELECT * FROM usuario";
$query = mysqli_query($conn, $sql); //mysqli_query FUNCIÓN de php para EJECUTAR SQL
/*Esta variable llamada query lo que hace es contener info. de la conexión (si está conectada o no a la BD) y a la CONSULTA que se necesita hacerl.*/
$message = "";

  // Consulta de todos los usuarios
  $sql = "SELECT * FROM usuario";
  $query = mysqli_query($conn, $sql);

  // Si se pasa un id_usuario por GET, cargamos los datos (para posible edición individual)
  $id_usuario = $_GET['id_usuario'] ?? null;
  $usuario = null;

  if ($id_usuario) {
      $sql = "SELECT * FROM usuario WHERE id_usuario = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "i", $id_usuario);
      mysqli_stmt_execute($stmt);
      $resultado = mysqli_stmt_get_result($stmt);
      $usuario = mysqli_fetch_assoc($resultado);
  }

// Obtener todos los usuarios para mostrar en la tabla
$usuarios = [];
while($row = mysqli_fetch_array($query)) {
    $usuarios[] = $row;
}
?>

  <!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Secretario</title>

    <!-- Bootstrap + Iconos + Google Fonts -->
     <!-- Bootstrap CSS + Iconos + letras -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  
    <!-- CSS propio -->
     <link rel="stylesheet" href="./../../../css/style.css">
  </head>

  <body class="grupo-user-page">

    <!-- Menú hamburguesa móvil -->
    <nav class="d-md-none">
      <div class="container-fluid">
        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
          <img class="menuResponsive" src="./../../../img/menu.png" alt="menu">
        </button>
        <img class="logoResponsive" src="./../../../img/logo.png" alt="logoResponsive">
      </div>
    </nav>

    <!-- Menú lateral móvil -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
      <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body d-flex flex-column">
        <div class="banner-parte-superior">
         <a href="./../secretario-bienvenida.php" class="mb-3">
          <i class="bi bi-arrow-left-circle-fill me-2"></i><span data-i18n="goBack">Volver</span>
        </a> </div>
        <a href="secretario-usuario.php" class="fw-semibold seleccionado mb-2" data-i18n="users" >Usuarios</a>
        <a href="../horario/horario-secretario.php" class="nav-opciones" data-i18n="schedule" >Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="nav-opciones" data-i18n="groups">Grupos</a>
        <a href="./../recurso/secretario-recurso.php" class="nav-opciones" data-i18n="resources">Recursos</a>
   
      </div>
    </div>

    <!-- Contenedor general -->
    <div class="contenedor">

      <!-- Barra lateral (pantallas grandes) -->
      <aside class="barra-lateral d-none d-md-flex flex-column">
           <div class="volverGeneral">
        <div class="volver">
        <a href="../secretario-bienvenida.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
        <a href="../secretario-bienvenida.php" data-i18n="goBack">Volver</a>
      </div>
      <i class="bi bi-translate traductor-menu"></i>
      </div>

        <a href="secretario-usuario.php" class="fw-semibold seleccionado mb-2" data-i18n="users">Usuarios</a>
        <a href="../horario/horario-secretario.php" class="nav-opciones" data-i18n="schedule">Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="nav-opciones" data-i18n="groups">Grupos</a>
        <a href="./../recurso/secretario-recurso.php" class="nav-opciones" data-i18n="resources">Recursos</a>
   
      </aside>

      <!-- Contenido principal -->
      <main class="principal">

        <img src="./../../../img/logo.png" alt="Logo" class="logo">
        <h2 data-i18n="users" > Usuarios</h2>
        <p data-i18n="manageUsers">Gestiona los usuarios: agregá nuevos o modificá los existentes.</p>


      <!-- Tabla para pantallas pequeñas -->
      <div class="tabla-grupos-usuarios-responsive usuarios">

      <button class="boton-opciones2 agregar colorfondorosa"
                            data-bs-toggle="modal"
                            data-bs-target="#modalGrupo"
                            onclick="document.getElementById('accion').value='insertar';">
                      <h4>+</h4></button>

        <?php foreach($usuarios as $row): ?>
          <div class="dia">

          <button class="boton-opciones miercoles">
              <?php echo htmlspecialchars($row['nombre_usuario'].' '.$row['apellido_usuario'], ENT_SUBSTITUTE) ?>
          </button>

          <div class="contenido-dia grupos-usuarios-responsive">

              <table class="tabla-grupos-usuarios-responsive ">
                  <tr><td><b>Cargo:</b> <?= htmlspecialchars($row['cargo_usuario']) ?></td></tr>
                  <tr><td><b>Cédula:</b> <?= htmlspecialchars($row['ci_usuario']) ?></td></tr>
                  <tr><td><b>Teléfono:</b> <?= htmlspecialchars($row['telefono_usuario']) ?></td></tr>
                  <tr><td><b>Email:</b> <?= htmlspecialchars($row['gmail_usuario']) ?></td></tr>
                  <tr class="editar">
                      <th class="grupos-usuarios-responsive">
                          <a class="editar btn" data-bs-toggle="modal" data-bs-target="#update_modal<?= $row['id_usuario'] ?>">
                        <i class="bi bi-pencil-square"></i>
                      </th>
                  </tr>
                  <tr>
                      <th class="grupos-usuarios-responsive">
                          <a href="delete_user_secretario.php?id_usuario=<?= $row['id_usuario'] ?>" class="eliminar btn">
                        <i class="bi bi-trash"></i>  </a>
                      </th>
                  </tr>
                
              </table>
          </div></div>
        <?php endforeach; ?>
      </div>

    

<!-- Tabla para pantallas grandes -->
<div class="table-responsive">
        <table class="tabla-secretario">
          <thead>
            <tr>
              <th data-i18n="name">Nombre</th>
              <th data-i18n="lastName">Apellido</th>
              <th >Email</th>
              <th data-i18n="phone" >Teléfono</th>
              <th data-i18n="idCard2">Cédula</th>
              <th data-i18n="position">Cargo</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
           <?php foreach($usuarios as $row): ?>
            <tr>
              <td><?= $row['nombre_usuario'] ?></td>
              <td><?= $row['apellido_usuario'] ?></td>
              <td><?= $row['gmail_usuario'] ?></td>
              <td><?= $row['telefono_usuario'] ?></td>
              <td><?= $row['ci_usuario'] ?></td>
              <td><?= $row['cargo_usuario'] ?></td>
              <td>
                  <a class="editar btn" data-bs-toggle="modal" data-bs-target="#update_modal<?= $row['id_usuario'] ?>">
                  <i class="bi bi-pencil-square"></i>
                </a>
              </td>
              <td>
                  <a href="delete_user_secretario.php?id_usuario=<?= $row['id_usuario'] ?>" class="eliminar btn">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>

            <!-- Modal edición -->
            <div class="modal fade" id="update_modal<?= $row['id_usuario'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" data-i18n="editUser">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="POST" action="./editar-usuario.php">
                    <div class="modal-body">
                      <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">

                      <div class="mb-3">
                        <label data-i18n="idCard">Cedula de identidad</label>
                        <input type="number" name="ci_usuario" class="form-control" value="<?= $row['ci_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label data-i18n="name">Nombre</label>
                        <input type="text" name="nombre_usuario" class="form-control" value="<?= $row['nombre_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label data-i18n="lastName">Apellido</label>
                        <input type="text" name="apellido_usuario" class="form-control" value="<?= $row['apellido_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="gmail_usuario" class="form-control" value="<?= $row['gmail_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label data-i18n="phone">Teléfono</label>
                        <input type="number" name="telefono_usuario" class="form-control" value="<?= $row['telefono_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label data-i18n="position">Cargo</label>
                        <select name="cargo_usuario" class="form-select">
                          <option value="" data-i18n="select">Seleccionar</option>
                          <option value="Docente" <?= ($row['cargo_usuario'] == 'Docente') ? 'selected' : '' ?>data-i18n="teacher">Docente</option>
                          <option value="Adscripto" <?= ($row['cargo_usuario'] == 'Adscripto') ? 'selected' : '' ?> data-i18n="adscripto">Adscripto</option>
                          <option value="Secretario" <?= ($row['cargo_usuario'] == 'Secretario') ? 'selected' : '' ?> data-i18n="secretary">Secretario</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label data-i18n="password">Contraseña</label>
                        <input type="password" name="contrasenia_usuario" class="form-control" value="<?= $row['contrasenia_usuario'] ?>">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="cancel">Cerrar</button>
                      <button type="submit" class="btn btn-primary" data-i18n="save">Guardar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <?php endforeach; ?>

            <tr><td colspan="8" class="text-center"> <!-- Une todas las columnas en una sola celda y centra el "+" -->
              <h4 class="agregar"
                          data-bs-toggle="modal"
                          data-bs-target="#modalUsuario"
                          onclick="document.getElementById('accion').value='insertar';">+
                </h4>
                
            </td></tr>

          </tbody>
        </table>
</div>

        <!-- Modal creación -->
        <div class="modal fade" id="modalUsuario" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" data-i18n="addUser">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <form method="POST" action="./agregar-usuario.php">
                <div class="modal-body">
                  <input type="hidden" id="accion" name="accion">

                  <div class="mb-3">
                    <label data-i18n="idCard">Cedula de identidad</label>
                    <input type="number" name="ci_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label data-i18n="name">Nombre</label>
                    <input type="text" name="nombre_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label data-i18n="lastName">Apellido</label>
                    <input type="text" name="apellido_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="gmail_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label data-i18n="phone">Teléfono</label>
                    <input type="number" name="telefono_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label data-i18n="position">Cargo</label>
                    <select name="cargo_usuario" class="form-select" required>
                      <option value="" data-i18n="select">Seleccionar</option>
                      <option value="Docente" data-i18n="teacher">Docente</option>
                      <option value="Adscripto" data-i18n="adscripto">Adscripto</option>
                      <option value="Secretario" data-i18n="secretary">Secretario</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label data-i18n="password">Contraseña</label>
                    <input type="password" name="contrasenia_usuario" class="form-control" required>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="cancel">Cerrar</button>
                  <button type="submit" data-i18n="save" class="btn btn-primary">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="../js/validation.js"></script>
    <script src="./../../../utils/desplegar-acordeon.js"></script>

     <!-- i18next desde CDN -->
  <script src="https://unpkg.com/i18next@21.6.16/dist/umd/i18next.min.js"></script>
  <script src="./../../../utils/translate.js"></script>

  </body>
  </html>
