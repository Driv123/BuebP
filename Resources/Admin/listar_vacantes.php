<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login/login.php');
    exit;
}

define('VACANTES_XML', __DIR__ . '/vacantes.xml');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vacantes Registradas – Panel Admin</title>
  <link rel="stylesheet" href="../../Styles/index_styles.css" />
</head>
<body>
<header>
  <div class="container header-flex">
    <div class="logo">
      <img src="../../Images/red guard(1).png" alt="Logo Red Guard" class="logo-img" />
      <div class="logo-text"><h1>Panel Administrador</h1></div>
    </div>
    <nav class="actions">
      <a href="vista_admin.php" class="btn-outline">Volver al Panel</a>
    </nav>
  </div>
</header>

<main class="container section-card">
  <h2>Vacantes Registradas</h2>

  <?php
  if (file_exists(VACANTES_XML)) {
      $xml = simplexml_load_file(VACANTES_XML);
      if (count($xml->vacante) > 0): ?>
        <table class="vacantes-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Título</th>
              <th>Empresa</th>
              <th>Ciudad</th>
              <th>Sueldo</th>
              <th>Duración</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($xml->vacante as $v): ?>
              <tr>
                <td><?php echo htmlspecialchars((string)$v['id']); ?></td>
                <td><?php echo htmlspecialchars((string)$v->titulo); ?></td>
                <td><?php echo htmlspecialchars((string)$v->empresa); ?></td>
                <td><?php echo htmlspecialchars((string)$v->ciudad); ?></td>
                <td><?php echo htmlspecialchars((string)$v->sueldo); ?></td>
                <td><?php echo htmlspecialchars((string)$v->duracion); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No hay vacantes registradas.</p>
      <?php endif;
  } else {
      echo "<p>⚠ Error: archivo vacantes.xml no encontrado.</p>";
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
