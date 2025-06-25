<?php
session_start();

define('ASPIRANTES_XML', __DIR__ . '/aspirantes.xml');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre'] ?? '');
    $apellido   = trim($_POST['apellido'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $telefono   = trim($_POST['telefono'] ?? '');
    $ciudad     = trim($_POST['ciudad'] ?? '');
    $educacion  = trim($_POST['educacion'] ?? '');
    $password   = trim($_POST['password'] ?? '');

    if ($nombre === '' || $apellido === '' || $correo === '' || $telefono === '' || $ciudad === '' || $educacion === '' || $password === '') {
        $error = '❌ Todos los campos son obligatorios.';
    } else {
        // Crear archivo si no existe
        if (!file_exists(ASPIRANTES_XML)) {
            $aspirantes = new SimpleXMLElement('<aspirantes></aspirantes>');
        } else {
            $aspirantes = simplexml_load_file(ASPIRANTES_XML);
        }

        // Crear nuevo nodo
        $nuevo = $aspirantes->addChild('aspirante');
        $nuevo->addAttribute('id', uniqid());
        $nuevo->addChild('nombre', htmlspecialchars($nombre));
        $nuevo->addChild('apellido', htmlspecialchars($apellido));
        $nuevo->addChild('correo', htmlspecialchars($correo));
        $nuevo->addChild('telefono', htmlspecialchars($telefono));
        $nuevo->addChild('ciudad', htmlspecialchars($ciudad));
        $nuevo->addChild('educacion', htmlspecialchars($educacion));
        $nuevo->addChild('password', password_hash($password, PASSWORD_DEFAULT)); // Guardar contraseña segura

        // Guardar archivo
        $aspirantes->asXML(ASPIRANTES_XML);
        $success = '✅ Registro exitoso. Ahora puedes iniciar sesión.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Aspirante</title>
  <link rel="stylesheet" href="../../Styles/crear_vacante_styles.css">
</head>
<body>
  <main class="container section-card">
    <h2>Registro de Aspirante</h2>

    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="form-perfil">
      <div class="form-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
      </div>
      <div class="form-group">
        <label>Apellido:</label>
        <input type="text" name="apellido" required>
      </div>
      <div class="form-group">
        <label>Correo:</label>
        <input type="email" name="correo" required>
      </div>
      <div class="form-group">
        <label>Teléfono:</label>
        <input type="tel" name="telefono" required>
      </div>
      <div class="form-group">
        <label>Ciudad:</label>
        <input type="text" name="ciudad" required>
      </div>
      <div class="form-group">
        <label>Formación Académica:</label>
        <input type="text" name="educacion" required>
      </div>
      <div class="form-group">
        <label>Contraseña:</label>
        <input type="password" name="password" required>
      </div>
      <div class="form-group">
        <button type="submit" class="btn-form">Registrar</button>
      </div>
    </form>
  </main>
</body>
</html>
