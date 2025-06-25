<?php
session_start();

// Verificar sesión de administrador
if (!isset($_SESSION['admin_username'])) {
    header('Location: login.php');
    exit;
}

define('ASPIRANTES_XML', __DIR__ . '/../registro/aspirantes.xml');
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

<?php
if (file_exists(ASPIRANTES_XML)) {
    $xml = simplexml_load_file(ASPIRANTES_XML);

    echo '
    <table class="vacantes-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>Ciudad</th>
        </tr>
      </thead>
      <tbody>';

    foreach ($xml->aspirante as $aspirante) {
        $id       = (string)$aspirante['id'];
        $nombre   = (string)$aspirante->nombre;
        $apellido = (string)$aspirante->apellido;
        $correo   = (string)$aspirante->correo;
        $telefono = (string)$aspirante->telefono;
        $ciudad   = (string)$aspirante->ciudad;

        echo "<tr>
                <td>$id</td>
                <td>$nombre</td>
                <td>$apellido</td>
                <td>$correo</td>
                <td>$telefono</td>
                <td>$ciudad</td>
              </tr>";
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
