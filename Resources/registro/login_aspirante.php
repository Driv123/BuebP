<?php
session_start();

define('ASPIRANTES_XML', __DIR__ . '/aspirantes.xml');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo    = trim($_POST['correo'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    if ($correo === '' || $password === '') {
        $error = 'Por favor completa ambos campos.';
    } elseif (file_exists(ASPIRANTES_XML)) {
        $xml = simplexml_load_file(ASPIRANTES_XML);
        $encontrado = false;

        foreach ($xml->aspirante as $aspirante) {
            if ((string)$aspirante->correo === $correo) {
                $hash = (string)$aspirante->password;
                if (password_verify($password, $hash)) {
                    // Iniciar sesión
                    $_SESSION['aspirante_id'] = (string)$aspirante['id'];
                    $_SESSION['aspirante_nombre'] = (string)$aspirante->nombre;
                    $_SESSION['aspirante_correo'] = (string)$aspirante->correo;
                    header('Location: aspirante_dashboard.php');
                    exit;
                }
                break;
            }
        }

        $error = 'Correo o contraseña incorrectos.';
    } else {
        $error = 'Error interno: no se encontró el archivo de aspirantes.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Aspirante</title>
  <link rel="stylesheet" href="../../Styles/login_style.css">
</head>
<body>
  <main class="container section-card">
    <h2>Iniciar Sesión - Aspirante</h2>
    <?php if ($error): ?>
      <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="form-perfil">
      <div class="form-group">
        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" id="correo" required>
      </div>

      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>
      </div>

      <div class="form-group">
        <button type="submit" class="btn-form">Iniciar Sesión</button>
      </div>
    </form>

    <div style="margin-top: 1rem;">
      <a href="registro_aspirante.php" class="btn-outline">Registrarme</a>
    </div>
  </main>
</body>
</html>
