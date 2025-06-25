<?php
session_start();

// 1) Verificar si el administrador está autenticado
if (!isset($_SESSION['admin_username'])) {
    header('Location: Resources/login/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Administrador – Red Guard</title>
  <!-- Ajusta la ruta al CSS según tu estructura de carpetas -->
  <link rel="stylesheet" href="../../Styles/vista_admin_styles.css" /> 
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
        <a href="../../index.html" class="btn-outline">Cerrar Sesión</a>
      </nav>
    </div>
  </header>

  <!-- MAIN -->
  <main class="container section-card">
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h2>
    <p>Desde este panel puedes gestionar vacantes y aspirantes registrados.</p>

    <ul class="opciones-list">
      <li><a href="crear_vacante.php">Crear Nueva Vacante</a></li>
      <li><a href="ver_vacantes.php">Editar Vacantes</a></li>
      <li><a href="listar_vacantes.php">Ver Vacantes</a></li>
      <li><a href="ver_todos_aspirantes.php">Ver Aspirantes Registrados</a></li>
      <li><a href="ver_aspirantes.php">Ver Aspirantes Postulados</a></li>
    </ul>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="container footer-content">
      <p>© 2025 Red Guard Technology. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
