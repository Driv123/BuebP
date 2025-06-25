<?php
session_start();

// Validar sesión activa
if (!isset($_SESSION['aspirante_id'])) {
    header('Location: login_aspirante.php');
    exit;
}

$nombre = $_SESSION['aspirante_nombre'] ?? 'Aspirante';
$correo = $_SESSION['aspirante_correo'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Aspirante</title>
  <link rel="stylesheet" href="../../Styles/aspirante_vista_style.css">
</head>
<body>
  <main class="container section-card">
    <h2>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h2>
    <p><strong>Correo:</strong> <?php echo htmlspecialchars($correo); ?></p>

    <div class="form-group" style="margin-top: 20px;">
      <a href="ver_vacantes_aspirante.php" class="btn-form">Ver Vacantes Disponibles</a>
    </div>
    <div class="form-group">
      <a href="estado_postulaciones.php" class="btn-form">Estado de Mis Postulaciones</a>
    </div>
      <div class="form-group">
      <a href="perfil.php" class="btn-form">Perfil</a>
    </div>


    <div class="form-group" style="margin-top: 30px;">
      <a href="../../index.html" class="btn-outline">Cerrar Sesión</a>
    </div>
  </main>
</body>
</html>
