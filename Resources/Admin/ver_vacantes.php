<?php
session_start();

// Verificar sesión de administrador
if (!isset($_SESSION['admin_username'])) {
    header('Location: Resources/login/login.php');
    exit;
}

// Ruta al XML de vacantes
define('VACANTES_XML', __DIR__ . '/vacantes.xml');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Listado de Vacantes – Panel Admin</title>
  <link rel="stylesheet" href="../../Styles/ver_vacantes_styles.css" />
</head>
<body>
  <!-- HEADER -->
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
      </nav>
    </div>
  </header>

  <!-- MAIN -->
  <main class="container">
    <h2>Vacantes Registradas</h2>

    <?php
    if (file_exists(VACANTES_XML)) {
        $xml = simplexml_load_file(VACANTES_XML) 
            or die('Error: No se pudo cargar el XML de vacantes.');

        if (count($xml->vacante) > 0) {
            echo '<table class="vacantes-table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Empresa</th>
                        <th>Ciudad</th>
                        <th>Sueldo</th>
                        <th>Duración</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>';
            foreach ($xml->vacante as $vacante) {
                $vid       = htmlspecialchars((string) $vacante->attributes()->id);
                $vtitulo   = htmlspecialchars((string) $vacante->titulo);
                $vempresa  = htmlspecialchars((string) $vacante->empresa);
                $vciudad   = htmlspecialchars((string) $vacante->ciudad);
                $vsueldo   = htmlspecialchars((string) $vacante->sueldo);
                $vduracion = htmlspecialchars((string) $vacante->duracion);

                echo '<tr>
                        <td>' . $vid . '</td>
                        <td>' . $vtitulo . '</td>
                        <td>' . $vempresa . '</td>
                        <td>' . $vciudad . '</td>
                        <td>' . $vsueldo . '</td>
                        <td>' . $vduracion . '</td>
                        <td>
                          <a href="editar_vacantes.php?id=' . $vid . '" class="btn-small">Editar</a>
                        </td>
                      </tr>';
            }
            echo '  </tbody>
                  </table>';
        } else {
            echo '<p>No hay vacantes registradas.</p>';
        }
    } else {
        echo '<p>Error: no se encontró el archivo vacantes.xml.</p>';
    }
    ?>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="container footer-content">
      <p>© 2025 Red Guard Technology. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
