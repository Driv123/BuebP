<?php
session_start();

define('ASPIRANTES_XML', __DIR__ . '/aspirantes.xml');
define('VACANTES_XML', __DIR__ . '/../Admin/vacantes.xml');

if (!isset($_SESSION['aspirante_id'])) {
    header('Location: login_aspirante.php');
    exit;
}

$aspirante_id = $_SESSION['aspirante_id'];
$postulaciones = [];

if (file_exists(ASPIRANTES_XML)) {
    $aspirantes = simplexml_load_file(ASPIRANTES_XML);
    foreach ($aspirantes->aspirante as $aspirante) {
        if ((string)$aspirante['id'] === $aspirante_id) {
            foreach ($aspirante->postulaciones->postulacion ?? [] as $p) {
                $postulaciones[] = [
                    'vacante_id' => (string)$p['vacante_id'],
                    'estado'     => (string)$p['estado'],
                ];
            }
            break;
        }
    }
}

function obtenerDatosVacante($id) {
    if (!file_exists(VACANTES_XML)) return null;

    $vacantes = simplexml_load_file(VACANTES_XML);
    foreach ($vacantes->vacante as $v) {
        if ((string)$v['id'] === $id) {
            return [
                'titulo'     => (string)$v->titulo,
                'empresa'    => (string)$v->empresa,
                'ciudad'     => (string)$v->ciudad,
                'sueldo'     => (string)$v->sueldo,
                'duracion'   => (string)$v->duracion,
                'descripcion'=> (string)$v->descripcion,
            ];
        }
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de tus Postulaciones</title>
    <link rel="stylesheet" href="../../Styles/estado_postulacion.css">
</head>
<body>
<main class="container section-card">
    <h2>Estado de tus Postulaciones</h2>
    <table class="vacantes-table">
        <thead>
            <tr>
                <th>ID Vacante</th>
                <th>Título</th>
                <th>Empresa</th>
                <th>Ciudad</th>
                <th>Sueldo</th>
                <th>Duración</th>
                <th>Descripción</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($postulaciones as $post): 
            $datos = obtenerDatosVacante($post['vacante_id']);
            $estado = ucfirst($post['estado']);
            $clase_estado = match(strtolower($estado)) {
                'aceptado' => 'estado-aceptado',
                'rechazado' => 'estado-rechazado',
                default => 'estado-proceso'
            };
        ?>
            <tr>
                <td><?= htmlspecialchars($post['vacante_id']) ?></td>
                <td><?= htmlspecialchars($datos['titulo'] ?? 'Vacante eliminada') ?></td>
                <td><?= htmlspecialchars($datos['empresa'] ?? '-') ?></td>
                <td><?= htmlspecialchars($datos['ciudad'] ?? '-') ?></td>
                <td><?= htmlspecialchars($datos['sueldo'] ?? '-') ?></td>
                <td><?= htmlspecialchars($datos['duracion'] ?? '-') ?></td>
                <td><?= htmlspecialchars($datos['descripcion'] ?? '-') ?></td>
                <td class="<?= $clase_estado ?>"><?= $estado ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="boton-volver">
        <a href="aspirante_dashboard.php" class="btn-outline">← Volver al Dashboard</a>
    </div>
</main>
</body>
</html>
