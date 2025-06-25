<?php
session_start();

// Verificar sesión del administrador
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login/login.php');
    exit;
}

define('VACANTES_XML', __DIR__ . '/vacantes.xml');

$error = '';
$success = '';
$vacanteData = [
    'id'          => '',
    'titulo'      => '',
    'empresa'     => '',
    'ciudad'      => '',
    'sueldo'      => '',
    'duracion'    => '',
    'descripcion' => ''
];

// Procesar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');

    if (!file_exists(VACANTES_XML)) {
        $error = 'Error: Archivo vacantes.xml no encontrado.';
    } elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
        // EDITAR con SimpleXML
        $titulo      = trim($_POST['titulo'] ?? '');
        $empresa     = trim($_POST['empresa'] ?? '');
        $ciudad      = trim($_POST['ciudad'] ?? '');
        $sueldo      = trim($_POST['sueldo'] ?? '');
        $duracion    = trim($_POST['duracion'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($titulo === '' || $empresa === '' || $ciudad === '' || $sueldo === '' || $duracion === '' || $descripcion === '') {
            $error = 'Todos los campos son obligatorios.';
        } else {
            $xml = simplexml_load_file(VACANTES_XML);
            $encontrada = false;
            foreach ($xml->vacante as $vacante) {
                if ((string)$vacante['id'] === $id) {
                    $vacante->titulo      = htmlspecialchars($titulo);
                    $vacante->empresa     = htmlspecialchars($empresa);
                    $vacante->ciudad      = htmlspecialchars($ciudad);
                    $vacante->sueldo      = htmlspecialchars($sueldo);
                    $vacante->duracion    = htmlspecialchars($duracion);
                    $vacante->descripcion = htmlspecialchars($descripcion);
                    $encontrada = true;
                    break;
                }
            }

            if ($encontrada) {
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($xml->asXML());
                $dom->save(VACANTES_XML);

                $success = "Vacante (ID=$id) actualizada correctamente.";
                header("Refresh: 2; URL=ver_vacantes.php");
                exit;
            } else {
                $error = "No se encontró la vacante con ID $id.";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        // ELIMINAR con DOMDocument
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->load(VACANTES_XML);

        $vacantes = $dom->getElementsByTagName('vacante');
        $nodeToRemove = null;

        foreach ($vacantes as $vacante) {
            if ($vacante->getAttribute('id') === $id) {
                $nodeToRemove = $vacante;
                break;
            }
        }

        if ($nodeToRemove !== null) {
            $nodeToRemove->parentNode->removeChild($nodeToRemove);
            $dom->save(VACANTES_XML);

            $success = "Vacante (ID=$id) eliminada correctamente.";
            header("Refresh: 2; URL=ver_vacantes.php");
            exit;
        } else {
            $error = "No se encontró la vacante con ID $id.";
        }
    }
}

// Cargar datos por GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = trim($_GET['id']);
    if (file_exists(VACANTES_XML)) {
        $xml = simplexml_load_file(VACANTES_XML);
        foreach ($xml->vacante as $vacante) {
            if ((string)$vacante['id'] === $id) {
                $vacanteData['id']          = $id;
                $vacanteData['titulo']      = (string)$vacante->titulo;
                $vacanteData['empresa']     = (string)$vacante->empresa;
                $vacanteData['ciudad']      = (string)$vacante->ciudad;
                $vacanteData['sueldo']      = (string)$vacante->sueldo;
                $vacanteData['duracion']    = (string)$vacante->duracion;
                $vacanteData['descripcion'] = (string)$vacante->descripcion;
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Vacante</title>
  <link rel="stylesheet" href="../../Styles/editar_vacantes_styles.css" />
</head>
<body>
<header>
  <div class="container header-flex">
    <div class="logo">
      <img src="../../Images/red guard(1).png" alt="Logo Red Guard" class="logo-img" />
      <div class="logo-text"><h1>Panel Administrador</h1></div>
    </div>
    <nav class="actions">
      <a href="ver_vacantes.php" class="btn-outline">Volver</a>
    </nav>
  </div>
</header>

<main class="container section-card">
  <h2>Editar Vacante</h2>

  <?php if ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
  <?php elseif ($success): ?>
    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <?php if ($vacanteData['id']): ?>
    <!-- Formulario editar -->
    <form method="POST" action="editar_vacantes.php?id=<?php echo htmlspecialchars($vacanteData['id']); ?>" class="form-perfil">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($vacanteData['id']); ?>" />
      <input type="hidden" name="action" value="edit" />

      <div class="form-group"><label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($vacanteData['titulo']); ?>" required />
      </div>

      <div class="form-group"><label for="empresa">Empresa:</label>
        <input type="text" id="empresa" name="empresa" value="<?php echo htmlspecialchars($vacanteData['empresa']); ?>" required />
      </div>

      <div class="form-group"><label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($vacanteData['ciudad']); ?>" required />
      </div>

      <div class="form-group"><label for="sueldo">Sueldo:</label>
        <input type="text" id="sueldo" name="sueldo" value="<?php echo htmlspecialchars($vacanteData['sueldo']); ?>" required />
      </div>

      <div class="form-group"><label for="duracion">Duración:</label>
        <input type="text" id="duracion" name="duracion" value="<?php echo htmlspecialchars($vacanteData['duracion']); ?>" required />
      </div>

      <div class="form-group"><label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($vacanteData['descripcion']); ?></textarea>
      </div>

      <div class="form-group">
        <button type="submit" class="btn-form">Guardar Cambios</button>
      </div>
    </form>

    <!-- Formulario eliminar -->
    <form method="POST" action="editar_vacantes.php?id=<?php echo htmlspecialchars($vacanteData['id']); ?>" class="form-perfil">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($vacanteData['id']); ?>" />
      <input type="hidden" name="action" value="delete" />
      <div class="form-group">
        <button type="submit" class="btn-form btn-danger" onclick="return confirm('¿Estás seguro que deseas eliminar esta vacante?')">
          Eliminar Vacante
        </button>
      </div>
    </form>
  <?php else: ?>
    <p>No se encontró la vacante.</p>
  <?php endif; ?>
</main>

<footer>
  <div class="container footer-content">
    <p>© 2025 Red Guard Technology. Todos los derechos reservados.</p>
  </div>
</footer>
</body>
</html>
