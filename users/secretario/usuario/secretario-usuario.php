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
  ?>

  <!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Secretario</title>

    <!-- Bootstrap + Iconos + Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS propio -->
<style>
  /* Estilo normal de tabla (pantallas grandes) */
.table-responsive {
  width: 100%;
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 12px;
  overflow: hidden;
}

.table th, .table td {
  padding: 12px;
  text-align: left;
  vertical-align: middle;
}

/* ======== VISTA CELULAR ======== */
@media (max-width: 768px) {
  .table thead {
    display: none; /* Ocultar encabezados */
  }

  .table, 
  .table tbody, 
  .table tr, 
  .table td {
    display: block;
    width: 100%;
  }

  .table tr {
    margin-bottom: 1rem;
    border: 1px solid #f3d6f9;
    border-radius: 10px;
    background-color: #ffe6ff;
    padding: 10px;
  }

  .table td {
    text-align: left;
    padding: 8px 10px;
    position: relative;
  }

  /* Mostrar la etiqueta del encabezado antes de cada campo */
  .table td::before {
    content: attr(data-label);
    font-weight: bold;
    display: block;
    margin-bottom: 3px;
    color: #7a2a8a;
  }

  /* Centrar iconos de editar y eliminar */
  .table td:last-child {
    text-align: center;
  }
}

</style>
  </head>

  <body>

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
        <a href="../secretario-bienvenida.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
        <i class="bi bi-translate traductor-menu"></i>
        </div>
        <a href="secretario-usuario.php" class="fw-semibold seleccionado mb-2">Usuarios</a>
        <a href="../horario/horario-secretario.php" class="nav-opciones">Horarios</a>
        <a href="../grupo/secretario-grupo.php" class="nav-opciones">Grupos</a>
        <a href="./../recurso/secretario-recurso.php" class="nav-opciones">Recursos</a>
   
      </div>
    </div>

    <!-- Contenedor general -->
    <div class="contenedor">

      <!-- Barra lateral (pantallas grandes) -->
      <aside class="barra-lateral d-none d-md-flex flex-column">
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
        <a href="./../recurso/secretario-recurso.php" class="nav-opciones">Recursos</a>
   
      </aside>

      <!-- Contenido principal -->
      <main class="principal">

        <img src="./../../../img/logo.png" alt="Logo" class="logo">
        <h2> Usuarios</h2>
        <p>Gestiona los usuarios: agregá nuevos o modificá los existentes.</p>


        <table class=" table tabla-secretario">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Cédula</th>
              <th>Cargo</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_array($query)): ?>
            <tr>
              <td data-label="Nombre"><?= $row['nombre_usuario'] ?></td>
              <td data-label="Apellido"><?= $row['apellido_usuario'] ?></td>
              <td data-label="Email"><?= $row['gmail_usuario'] ?></td>
              <td data-label="Teléfono"><?= $row['telefono_usuario'] ?></td>
              <td  data-label="Cédula"><?= $row['ci_usuario'] ?></td>
              <td data-label="Cargo"><?= $row['cargo_usuario'] ?></td>
              <td data-label="Acciones">
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
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="POST" action="./editar-usuario.php">
                    <div class="modal-body">
                      <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">

                      <div class="mb-3">
                        <label>C.I</label>
                        <input type="number" name="ci_usuario" class="form-control" value="<?= $row['ci_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre_usuario" class="form-control" value="<?= $row['nombre_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label>Apellido</label>
                        <input type="text" name="apellido_usuario" class="form-control" value="<?= $row['apellido_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label>Gmail</label>
                        <input type="email" name="gmail_usuario" class="form-control" value="<?= $row['gmail_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label>Teléfono</label>
                        <input type="number" name="telefono_usuario" class="form-control" value="<?= $row['telefono_usuario'] ?>">
                      </div>

                      <div class="mb-3">
                        <label>Cargo</label>
                        <select name="cargo_usuario" class="form-select">
                          <option value="">Seleccionar</option>
                          <option value="Docente" <?= ($row['cargo_usuario'] == 'Docente') ? 'selected' : '' ?>>Docente</option>
                          <option value="Adscripto" <?= ($row['cargo_usuario'] == 'Adscripto') ? 'selected' : '' ?>>Adscripto</option>
                          <option value="Secretario" <?= ($row['cargo_usuario'] == 'Secretario') ? 'selected' : '' ?>>Secretario</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="contrasenia_usuario" class="form-control" value="<?= $row['contrasenia_usuario'] ?>">
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
            <?php endwhile; ?>

            <tr><td colspan="7" class="text-center"> <!-- Une todas las columnas en una sola celda y centra el "+" -->
              <h4 class="agregar"
                          data-bs-toggle="modal"
                          data-bs-target="#modalUsuario"
                          onclick="document.getElementById('accion').value='insertar';">+
                </h4>
                <td></td>
            </td></tr>

          </tbody>
        </table>

        <!-- Modal creación -->
        <div class="modal fade" id="modalUsuario" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <form method="POST" action="./agregar-usuario.php">
                <div class="modal-body">
                  <input type="hidden" id="accion" name="accion">

                  <div class="mb-3">
                    <label>C.I</label>
                    <input type="number" name="ci_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Apellido</label>
                    <input type="text" name="apellido_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Gmail</label>
                    <input type="email" name="gmail_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Teléfono</label>
                    <input type="number" name="telefono_usuario" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label>Cargo</label>
                    <select name="cargo_usuario" class="form-select" required>
                      <option value="">Seleccionar</option>
                      <option value="Docente">Docente</option>
                      <option value="Adscripto">Adscripto</option>
                      <option value="Secretario">Secretario</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Contraseña</label>
                    <input type="password" name="contrasenia_usuario" class="form-control" required>
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
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/validation.js"></script>
  </body>
  </html>
