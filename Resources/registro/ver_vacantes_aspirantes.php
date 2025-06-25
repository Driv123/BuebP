<?php
define('VACANTES_XML', __DIR__ . '/../Admin/vacantes.xml');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vacantes Disponibles</title>
  <link rel="stylesheet" href="../../Styles/ver_vacantes_styles.css">
</head>
<body>
<header>
  <div class="container header-flex">
    <div class="logo">
      <img src="../../Images/red guard(1).png" alt="Logo Red Guard" class="logo-img" />
      <div class="logo-text"><h1>Vacantes Disponibles</h1></div>
    </div>
    <nav class="actions">
      <a href="../../index.html" class="btn-outline">Volver al Inicio</a>
    </nav>
  </div>
</header>

<main class="container section-card">
  <h2>Oportunidades Actuales</h2>

  <?php
  if (file_exists(VACANTES_XML)) {
      $xml = simplexml_load_file(VACANTES_XML);
      if (count($xml->vacante) > 0): ?>
        <table class="vacantes-table">
          <thead>
            <tr>
              <th>Título</th>
              <th>Empresa</th>
              <th>Ciudad</th>
              <th>Sueldo</th>
              <th>Duración</th>
              <th>Descripción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($xml->vacante as $v): ?>
              <tr>
                <td><?php echo htmlspecialchars((string)$v->titulo); ?></td>
                <td><?php echo htmlspecialchars((string)$v->empresa); ?></td>
                <td><?php echo htmlspecialchars((string)$v->ciudad); ?></td>
                <td><?php echo htmlspecialchars((string)$v->sueldo); ?></td>
                <td><?php echo htmlspecialchars((string)$v->duracion); ?></td>
                <td><?php echo htmlspecialchars((string)$v->descripcion); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No hay vacantes disponibles por el momento.</p>
      <?php endif;
  } else {
      echo "<p>⚠ Error: no se encontró el archivo vacantes.xml.</p>";
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
