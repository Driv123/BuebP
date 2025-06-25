<?php
session_start();

$error = '';

// Si el formulario se envió por POST, procesamos login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos datos del formulario
    $inputUser = trim($_POST['username'] ?? '');
    $inputPass = trim($_POST['password'] ?? '');

    if ($inputUser === '' || $inputPass === '') {
        $error = 'Debes ingresar usuario y contraseña.';
    } else {
        // Intentamos cargar el XML de usuarios desde el mismo directorio
        $xml = @simplexml_load_file("usuarios.xml") 
               or die("Error: No se pudo cargar el archivo XML.");

        $found = false;
        // Recorremos cada <user> del XML
        foreach ($xml->user as $user) {
            $uname = (string) $user->username;
            $upass = (string) $user->password;

            if ($inputUser === $uname && $inputPass === $upass) {
                // Credenciales válidas
                $_SESSION['admin_username'] = $uname;
                $found = true;
                break;
            }
        }

        if ($found) {
            // Redirigir al dashboard de administrador
            // Ajusta la ruta según dónde esté tu archivo admin_dashboard.php
            header('Location: ./vista_admin.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Red Guard – Login Administrador</title>
  <!-- Ajusta la ruta al CSS según tu estructura de carpetas -->
  <link rel="stylesheet" href="../../Styles/login_style.css" />
</head>
<body>
  <header>
    <div class="container header-flex">
      <div class="logo">
        <!-- Ajusta la ruta a la imagen según tu estructura -->
        <img src="../../Images/red guard(1).png" alt="Logo Red Guard" class="logo-img" />
        <div class="logo-text">
          <h1>RED GUARD</h1>
          <p class="slogan">Login Administrador</p>
        </div>
      </div>
    </div>
  </header>

  <main class="container section-card">
    <h2>Acceso Administrador</h2>

    <!-- Si hay error, lo mostramos aquí -->
    <?php if ($error !== ''): ?>
      <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="form-perfil">
      <div class="form-group">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" placeholder="admin" required />
      </div>

      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required />
      </div>

      <div class="form-group">
        <button type="submit" class="btn-form">Iniciar Sesión</button>
      </div>
    </form>
  </main>

  <footer>
    <div class="container footer-content">
      <p>© 2025 Red Guard Technology. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
