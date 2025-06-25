<?php
session_start();

define('ASPIRANTES_XML', __DIR__ . '/aspirantes.xml');

// Verificar si el aspirante inició sesión
if (!isset($_SESSION['aspirante_id'])) {
    header('Location: login_aspirante.php');
    exit;
}

$aspirante_id = $_SESSION['aspirante_id'];
$aspiranteData = null;

if (file_exists(ASPIRANTES_XML)) {
    $xml = simplexml_load_file(ASPIRANTES_XML);

    foreach ($xml->aspirante as $aspirante) {
        if ((string)$aspirante['id'] === $aspirante_id) {
            $aspiranteData = $aspirante;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil - Aspirante</title>
  <link rel="stylesheet" href="../../Styles/perfil_aspirante_styles.css">
</head>
<body>
<header>
  <div class="container header-flex">
    <div class="logo">
      <img src="../../Images/red guard(1).png" alt="Logo Red Guard" class="logo-img" />
      <div class="logo-text"><h1>Panel Aspirante</h1></div>
    </div>
    <nav class="actions">
      <a href="aspirante_dashboard.php" class="btn-outline">Volver</a>
    </nav>
  </div>
</header>

<main class="container section-card">
  <h2>Mi Perfil</h2>

  <?php if ($aspiranteData): ?>
    <table class="vacantes-table">
      <tr><th>Nombre</th><td><?= htmlspecialchars($aspiranteData->nombre) ?></td></tr>
      <tr><th>Apellido</th><td><?= htmlspecialchars($aspiranteData->apellido) ?></td></tr>
      <tr><th>Correo</th><td><?= htmlspecialchars($aspiranteData->correo) ?></td></tr>
      <tr><th>Teléfono</th><td><?= htmlspecialchars($aspiranteData->telefono) ?></td></tr>
      <tr><th>Ciudad</th><td><?= htmlspecialchars($aspiranteData->ciudad) ?></td></tr>
    </table>
  <?php else: ?>
    <p>No se pudo cargar la información del aspirante.</p>
  <?php endif; ?>
</main>

<footer>
  <div class="container footer-content">
    <p>© 2025 Red Guard Technology. Todos los derechos reservados.</p>
  </div>
</footer>
</body>
</html>
