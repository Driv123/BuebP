<?php
session_start();

define('VACANTES_XML', __DIR__ . '/../Admin/vacantes.xml');
define('ASPIRANTES_XML', __DIR__ . '/aspirantes.xml');

// Validar sesión
if (!isset($_SESSION['aspirante_id'])) {
    header('Location: login_aspirante.php');
    exit;
}

$aspirante_id = $_SESSION['aspirante_id'];
$success = '';
$error = '';

// Procesar postulación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vacante_id'])) {
    $vacante_id = trim($_POST['vacante_id']);

    if (file_exists(ASPIRANTES_XML)) {
        $xml = simplexml_load_file(ASPIRANTES_XML);

        foreach ($xml->aspirante as $aspirante) {
            if ((string)$aspirante['id'] === $aspirante_id) {
                // Verificar si ya se postuló
                foreach ($aspirante->postulaciones->postulacion ?? [] as $postulacion) {
                    if ((string)$postulacion['vacante_id'] === $vacante_id) {
                        $error = 'Ya te has postulado a esta vacante.';
                        break 2;
                    }
                }

                // Agregar nueva postulación
                if (!isset($aspirante->postulaciones)) {
                    $aspirante->addChild('postulaciones');
                }

                $nueva = $aspirante->postulaciones->addChild('postulacion');
                $nueva->addAttribute('vacante_id', $vacante_id);
                $nueva->addAttribute('estado', 'en_proceso');

                // Guardar cambios
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($xml->asXML());
                $dom->save(ASPIRANTES_XML);

                $success = 'Postulación realizada correctamente.';
                break;
            }
        }
    } else {
        $error = 'No se encontró el archivo de aspirantes.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Vacantes Disponibles</title>
  <link rel="stylesheet" href="../../Styles/ver_vacante_styles.css">
</head>
<body>
  <main class="container section-card">
    <h2>Vacantes Disponibles</h2>

    <?php if ($success): ?>
      <div class="success-message"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
      <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php
    if (file_exists(VACANTES_XML)) {
        $vacantes = simplexml_load_file(VACANTES_XML);

        if (count($vacantes->vacante) > 0) {
            echo '<table class="vacantes-table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Empresa</th>
                        <th>Ciudad</th>
                        <th>Sueldo</th>
                        <th>Duración</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>';

            foreach ($vacantes->vacante as $v) {
                $id = (string)$v['id'];
                echo '<tr>
                        <td>' . htmlspecialchars($id) . '</td>
                        <td>' . htmlspecialchars($v->titulo) . '</td>
                        <td>' . htmlspecialchars($v->empresa) . '</td>
                        <td>' . htmlspecialchars($v->ciudad) . '</td>
                        <td>' . htmlspecialchars($v->sueldo) . '</td>
                        <td>' . htmlspecialchars($v->duracion) . '</td>
                        <td>
                          <form method="POST">
                            <input type="hidden" name="vacante_id" value="' . htmlspecialchars($id) . '">
                            <button type="submit" class="btn-form">Postularme</button>
                          </form>
                        </td>
                      </tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>No hay vacantes disponibles.</p>';
        }
    } else {
        echo '<p>Error: no se encontró el archivo vacantes.xml.</p>';
    }
    ?>
    
    <div class="form-group" style="margin-top: 30px;">
      <a href="aspirante_dashboard.php" class="btn-outline">Volver al Panel</a>
    </div>
  </main>
</body>
</html>
