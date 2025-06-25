<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header('Location: Resources/login/login.php');
    exit;
}


$error = '';
$success = '';

define('VACANTES_XML', __DIR__ . '/vacantes.xml');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo     = trim($_POST['titulo']     ?? '');
    $empresa    = trim($_POST['empresa']    ?? '');
    $ciudad     = trim($_POST['ciudad']     ?? '');
    $sueldo     = trim($_POST['sueldo']     ?? '');
    $duracion   = trim($_POST['duracion']   ?? '');
    $descripcion= trim($_POST['descripcion']?? '');

    if ($titulo === '' || $empresa === '' || $ciudad === '' || $sueldo === '' || $duracion === '' || $descripcion === '') {
        $error = 'Todos los campos son obligatorios.';
    } else {
        if (file_exists(VACANTES_XML)) {
            $xml = simplexml_load_file(VACANTES_XML) 
                or die('Error: No se pudo cargar el XML de vacantes.');
        } else {
            $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><vacantes></vacantes>';
            $xml = simplexml_load_string($xmlContent);
        }
        $maxId = 0;
        foreach ($xml->vacante as $v) {
            $attrId = (int) $v->attributes()->id;
            if ($attrId > $maxId) {
                $maxId = $attrId;
            }
        }
        $newId = $maxId + 1;

        $vacante = $xml->addChild('vacante');
        $vacante->addAttribute('id', $newId);
        $vacante->addChild('titulo',      htmlspecialchars($titulo));
        $vacante->addChild('empresa',     htmlspecialchars($empresa));
        $vacante->addChild('ciudad',      htmlspecialchars($ciudad));
        $vacante->addChild('sueldo',      htmlspecialchars($sueldo));
        $vacante->addChild('duracion',    htmlspecialchars($duracion));
        $vacante->addChild('descripcion', htmlspecialchars($descripcion));

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput       = true;
        $dom->loadXML($xml->asXML());
        $dom->save(VACANTES_XML);

        $success = 'Vacante agregada correctamente (ID = ' . $newId . ').';
        $titulo = $empresa = $ciudad = $sueldo = $duracion = $descripcion = '';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear Vacante – Panel Admin</title>
  <link rel="stylesheet" href="../../Styles/crear_vacante_styles.css" />
</head>
<body>
  <header>
    <div class="container header-flex">
      <div class="logo">
        <img src="Images/red guard(1).png" alt="Logo Red Guard" class="logo-img" />
        <div class="logo-text">
          <h1>Panel Administrador</h1>
        </div>
      </div>
      <nav class="actions">
        <a href="vista_admin.php" class="btn-outline">Volver al Dashboard</a>
        <a href="logout.php" class="btn-outline">Cerrar Sesión</a>
      </nav>
    </div>
  </header>

  <!-- MAIN -->
  <main class="container section-card">
    <h2>Crear Nueva Vacante</h2>

    <!-- Mostrar error o éxito -->
    <?php if ($error !== ''): ?>
      <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php elseif ($success !== ''): ?>
      <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- FORMULARIO PARA AGREGAR VACANTE -->
    <form action="crear_vacante.php" method="POST" class="form-perfil">
      <div class="form-group">
        <label for="titulo">Título del Cargo:</label>
        <input type="text" id="titulo" name="titulo" 
               value="<?php echo htmlspecialchars($titulo ?? ''); ?>" 
               placeholder="Ej. Analista de Seguridad de Redes" required />
      </div>

      <div class="form-group">
        <label for="empresa">Nombre de la Empresa:</label>
        <input type="text" id="empresa" name="empresa"
               value="<?php echo htmlspecialchars($empresa ?? ''); ?>"
               placeholder="Ej. Red Guard Technology" required />
      </div>

      <div class="form-group">
        <label for="ciudad">Ciudad donde se Emplea:</label>
        <input type="text" id="ciudad" name="ciudad"
               value="<?php echo htmlspecialchars($ciudad ?? ''); ?>"
               placeholder="Ej. Ciudad de México" required />
      </div>

      <div class="form-group">
        <label for="sueldo">Sueldo:</label>
        <input type="text" id="sueldo" name="sueldo"
               value="<?php echo htmlspecialchars($sueldo ?? ''); ?>"
               placeholder="Ej. $20,000 MXN" required />
      </div>

      <div class="form-group">
        <label for="duracion">Tiempo de Contrato:</label>
        <input type="text" id="duracion" name="duracion"
               value="<?php echo htmlspecialchars($duracion ?? ''); ?>"
               placeholder="Ej. Tiempo Indeterminado" required />
      </div>

      <div class="form-group">
        <label for="descripcion">Descripción General:</label>
        <textarea id="descripcion" name="descripcion" rows="4" 
                  placeholder="Breve descripción de responsabilidades" required><?php
                  echo htmlspecialchars($descripcion ?? '');
                  ?></textarea>
      </div>

      <div class="form-group">
        <button type="submit" class="btn-form">Agregar Vacante</button>
      </div>
    </form>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="container footer-content">
      <p>© 2025 Red Guard Technology. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
