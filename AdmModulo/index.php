<?php
require_once 'controlador.php';
require_once '../configLogin/session_manager.php';
require_once '../configLogin/menu_dinamico.php';

SessionManager::startSession();

// Verificar permisos con actualización en tiempo real
if (!SessionManager::isLoggedIn()) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit();
}

// Recargar módulos si es necesario (para asegurar permisos actualizados)
SessionManager::reloadUserModules();

// Verificar permiso para AdmModulo
if (!SessionManager::hasPermission('AdmModulo')) {
    header('HTTP/1.0 403 Forbidden');
    exit('No tienes permiso para acceder a esta página');
}

$controller = new ModuloController();
$perfiles = $controller->obtenerTodosPerfiles();

// Validar y obtener perfil seleccionado
$idPerfilSeleccionado = isset($_POST['perfil']) ? (int)$_POST['perfil'] : ($perfiles[0]['Id_p'] ?? null);

// Procesar formulario de actualización
$mensaje = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modulos'])) {
    $modulosSeleccionados = array_map('intval', $_POST['modulos'] ?? []);
    
    if ($controller->actualizarModulos($idPerfilSeleccionado, $modulosSeleccionados)) {
        $mensaje = "¡Configuración guardada correctamente!";
        
        // Si el perfil modificado es el del usuario actual, actualizar sus módulos en sesión
        if (SessionManager::getCurrentProfileId() == $idPerfilSeleccionado) {
            $_SESSION['user']['modules'] = $controller->obtenerModulosPorPerfil($idPerfilSeleccionado);
        }
    } else {
        $error = "Error al guardar la configuración. Intente nuevamente.";
    }
}

// Obtener datos para la vista
$todosModulos = $controller->obtenerTodosModulos();
$modulosAsignados = $controller->obtenerModulosPorPerfil($idPerfilSeleccionado);
$idsModulosAsignados = array_column($modulosAsignados, 'Id_mod');

// Función auxiliar para obtener el nombre del perfil seleccionado
function obtenerNombrePerfil($perfiles, $idPerfil) {
    foreach ($perfiles as $perfil) {
        if ($perfil['Id_p'] == $idPerfil) {
            return htmlspecialchars($perfil['Nombre']);
        }
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Módulos por Perfil</title>
</head>
<body>
    <h1>Asignación de Módulos por Perfil</h1>

    <!-- Menú de navegación dinámico -->
    <?php echo generarMenuDinamico(); ?>

    <!-- Mensajes de feedback -->
    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <p><?= $error ?></p>
    <?php endif; ?>

    <!-- Selección de perfil -->
    <form method="post" action="index.php">
        <label for="perfil">Seleccionar Perfil:</label>
        <select name="perfil" id="perfil" onchange="this.form.submit()">
            <?php foreach ($perfiles as $perfil): ?>
                <option value="<?= $perfil['Id_p'] ?>" <?= $perfil['Id_p'] == $idPerfilSeleccionado ? 'selected' : '' ?>>
                    <?= htmlspecialchars($perfil['Nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Formulario de asignación de módulos -->
    <form method="post" action="index.php">
        <input type="hidden" name="perfil" value="<?= $idPerfilSeleccionado ?>">
        <table border="1">
            <tr>
                <th>Perfil</th>
                <th>Módulo</th>
                <th>Acceso</th>
            </tr>
            <?php foreach ($todosModulos as $modulo): ?>
                <tr>
                    <td><?= obtenerNombrePerfil($perfiles, $idPerfilSeleccionado) ?></td>
                    <td><?= htmlspecialchars($modulo['Nombre']) ?></td>
                    <td align="center">
                        <input type="checkbox" name="modulos[]" value="<?= $modulo['Id_mod'] ?>" 
                            <?= in_array($modulo['Id_mod'], $idsModulosAsignados) ? 'checked' : '' ?>>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" align="center">
                    <button type="submit">Guardar Configuración</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>