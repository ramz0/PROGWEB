<?php
// filepath: c:\xampp\htdocs\PROGWEB\AdmBitacora\index.php
require_once 'controlador.php';
require_once '../configLogin/menu_dinamico.php';

// Crear instancia del controlador
$controller = new BitacoraController();

// Obtener registros de la bitácora
$registros = $controller->obtenerRegistros();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Bitácora</title>
</head>
<body>
    <!-- Menú dinámico -->
    <header>
        <h1>Administración de Bitácora</h1>
        <nav>
            <?php echo generarMenuDinamico(); ?>
        </nav>
    </header>

    <!-- Tabla de bitácora -->
<main>
     <h2>Registros de Bitácora</h2>
        <form method="GET" action="index.php">
            <label for="nombre">Buscar por nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
            <button type="submit">Buscar</button>
        </form>
        <h2>Registros de Bitácora</h2>
        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Perfil</th>
                    <th>Nombre</th>
                    <th>Nick</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener el filtro de búsqueda
                $nombre = $_GET['nombre'] ?? null;
                $registros = $controller->obtenerRegistros($nombre);

                foreach ($registros as $registro): ?>
                    <tr>
                        <td><?= htmlspecialchars($registro['perfil'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($registro['nombre'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($registro['nick'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($registro['mod'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($registro['accion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>