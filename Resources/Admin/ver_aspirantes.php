<?php
session_start();

// Verificar sesión de administrador
if (!isset($_SESSION['admin_username'])) {
    header('Location: login.php');
    exit;
}

define('ASPIRANTES_XML', __DIR__ . '/../registro/aspirantes.xml');
$success = '';
$error = '';

// Procesar acciones (aceptar/rechazar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aspirante_id'], $_POST['vacante_id'], $_POST['accion'])) {
    $aspirante_id = $_POST['aspirante_id'];
    $vacante_id   = $_POST['vacante_id'];
    $accion       = $_POST['accion']; // aceptar o rechazar

    if (file_exists(ASPIRANTES_XML)) {
        $xml = simplexml_load_file(ASPIRANTES_XML);
        foreach ($xml->aspirante as $aspirante) {
            if ((string)$aspirante['id'] === $aspirante_id) {
                foreach ($aspirante->postulaciones->postulacion ?? [] as $postulacion) {
                    if ((string)$postulacion['vacante_id'] === $vacante_id) {
                        $postulacion['estado'] = ($accion === 'aceptar') ? 'aceptado' : 'rechazado';
                        $xml->asXML(ASPIRANTES_XML);
                        $success = 'Estado actualizado correctamente.';
                        break 2;
                    }
                }
            }
        }
    } else {
        $error = 'No se encontró el archivo aspirantes.xml.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Aspirantes Registrados</title>
  <link rel="stylesheet" href="../../Styles/vista_admin_styles.css" />
</head>
<body>
<header>
  <div class="container header-flex">
    <div class="logo">
      <img src="../../Images/red guard(1).png" alt="Logo Red Guard" class="logo-img" />
      <div class="logo-text"><h1>Panel Administrador</h1></div>
    </div>
    <nav class="actions">
      <a href="vista_admin.php" class="btn-outline">Volver al Dashboard</a>
    </nav>
  </div>
</header>

<main class="container section-card">
  <h2>Lista de Aspirantes</h2>

  <?php if ($success): ?>
    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
  <?php elseif ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

<?php
if (file_exists(ASPIRANTES_XML)) {
    $xml = simplexml_load_file(ASPIRANTES_XML);

    echo '
    <table class="vacantes-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Vacante ID</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>';

    foreach ($xml->aspirante as $aspirante) {
        $id     = (string)$aspirante['id'];
        $nombre = (string)$aspirante->nombre . ' ' . (string)$aspirante->apellido;
        $correo = (string)$aspirante->correo;

        // Tiene postulaciones
        if (isset($aspirante->postulaciones) && count($aspirante->postulaciones->postulacion) > 0) {
            foreach ($aspirante->postulaciones->postulacion as $post) {
                $vacId = (string)$post['vacante_id'];
                $estado = (string)$post['estado'];

                echo "<tr>
                        <td>$id</td>
                        <td>$nombre</td>
                        <td>$correo</td>
                        <td>$vacId</td>
                        <td>$estado</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                              <input type='hidden' name='aspirante_id' value='$id'>
                              <input type='hidden' name='vacante_id' value='$vacId'>
                              <button type='submit' name='accion' value='aceptar' class='btn-form'>Aceptar</button>
                            </form>
                            <form method='POST' style='display:inline; margin-left: 4px;'>
                              <input type='hidden' name='aspirante_id' value='$id'>
                              <input type='hidden' name='vacante_id' value='$vacId'>
                              <button type='submit' name='accion' value='rechazar' class='btn-form btn-danger'>Rechazar</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr>
                    <td>$id</td>
                    <td>$nombre</td>
                    <td>$correo</td>
                    <td colspan='3'>Sin postulaciones</td>
                  </tr>";
        }
    }

    echo '</tbody></table>';
} else {
    echo '<p>No se encontró el archivo de aspirantes.</p>';
}
?>
</main>

<footer>
  <div class="container footer-content">
    <p>© 2025 Red Guard Technology. Todos los derechos reservados.</p>
  </div>
</footer>
</body>
</html>
