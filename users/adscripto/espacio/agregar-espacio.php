<?php 
include('./../../../conexion.php');
$con = conectar_bd();

$id_espacio = $_GET['id_espacio'] ?? null;

if (!$id_espacio) {
  echo "<script>alert('Error: Espacio no especificado.'); window.history.back();</script>";
  exit;
}

// Obtener los atributos existentes del espacio
$stmt = $con->prepare("SELECT nombre_atributo, cantidad_atributo FROM espacio_atributo WHERE id_espacio = ?");
$stmt->bind_param("i", $id_espacio);
$stmt->execute();
$result = $stmt->get_result();

$atributosExistentes = [];
while ($row = $result->fetch_assoc()) {
  $atributosExistentes[$row['nombre_atributo']] = $row['cantidad_atributo'];
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel adscripto</title>
    <!-- Bootstrap CSS + Iconos + letras-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS propio -->
    <link rel="stylesheet" href="./../../../css/style.css">
</head>

<body>

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
      <a href="adscripto-espacio.php" class="mb-3"><i class="bi bi-arrow-left-circle-fill me-2"></i>Volver</a>
      <i class="bi bi-translate traductor-menu"></i>    
      </div>

        <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docentes</a>
      <a href="../materia/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
    </div>
  </div>

  <!-- Contenedor general -->
  <div class="container-fluid">
    <div class="row">

      <!-- Banner pantallas grandes -->
      <div class="col-md-3 barra-lateral d-none d-md-flex">
        <div class="volverGeneral">
          <div class="volver">
            <a href="adscripto-espacio.php"><i class="bi bi-arrow-left-circle-fill icono-volver"></i></a>
            <a href="adscripto-espacio.php">Volver</a>
          </div>
          <i class="bi bi-translate traductor-menu"></i>
        </div>

       <a href="adscripto-espacio.php" class="fw-semibold seleccionado mb-2">Espacio</a>
      <a href="../reserva-adscripto.php" class="nav-opciones mb-2">Reserva</a>
      <a href="../falta-docente.php" class="nav-opciones mb-2">Falta docentes</a>
      <a href="../materia/carga-materias.php" class="nav-opciones mb-2">Cargar materias</a>
    </div>


<!-- Contenido principal -->
<main class="col-md-9 principal" >
<img src="./../../../img/logo.png" alt="Logo" class="logo"> 
<div class="container my-4 espacio-contenedor">

<!-- Título con input -->
<div class="espacio-titulo centrar text-center mb-4">
  <input type="text" class="espacio-input" placeholder="Nombre del salón">
</div>

<!-- Formulario -->
          <form method="POST" action="atributo-accion.php?id_espacio=<?php echo $id_espacio; ?>" class="text-center">
            <div class="row fw-bold mb-2">
              <div class="col-4"></div>
              <div class="col-4">Cuenta con:</div>
              <div class="col-4">Cantidad:</div>
            </div>

            <?php
            $atributos = [
              'Mesas',
              'Sillas',
              'Proyector',
              'Televisor',
              'Aire Acondicionado'
            ];

            foreach ($atributos as $nombre) {
              $idInput = strtolower(str_replace(' ', '_', $nombre));
              $cantidad = $atributosExistentes[$nombre] ?? '';
              $checked = $cantidad ? 'checked' : '';
              $disabled = $cantidad ? '' : 'disabled';

              echo "
              <div class='row align-items-center mb-2'>
                <div class='col-4 text-end'>$nombre</div>
                <div class='col-4'>
                  <input type='checkbox' class='form-check-input toggleCantidad' data-target='cantidad_$idInput' $checked>
                </div>
                <div class='col-4'>
                  <input type='number' name='$idInput' id='cantidad_$idInput' value='$cantidad' class='form-control form-control-sm bg-light border-0' $disabled>
                </div>
              </div>
              ";
            }
            ?>

            <div class="text-center mt-4">
              <button type="submit" class="btn centrar espacio-btn">Guardar atributos</button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script: habilitar/deshabilitar inputs -->
  <script>
  document.querySelectorAll('.toggleCantidad').forEach(chk => {
    chk.addEventListener('change', function() {
      const target = document.getElementById(this.dataset.target);
      target.disabled = !this.checked;
      if (!this.checked) target.value = '';
    });
  });
  </script>
</body>
</html>