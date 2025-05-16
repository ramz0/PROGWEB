<?php
require_once 'controlador.php';
require_once '../configLogin/session_manager.php';
require_once '../configLogin/menu_dinamico.php';

SessionManager::startSession();
$usuarioLogueado = SessionManager::getCurrentUser();

$controller = new ModuloController();

// Obtener todos los perfiles
$perfiles = [
    ['Id_p' => 1001, 'Nombre' => 'Administrador'],
    ['Id_p' => 1002, 'Nombre' => 'Profesor'],
    ['Id_p' => 1003, 'Nombre' => 'Estudiante']
];

// Perfil seleccionado (por defecto el primero)
$idPerfilSeleccionado = $_POST['perfil'] ?? $perfiles[0]['Id_p'];

// Procesar formulario de actualización
$mensaje = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modulos'])) {
    $modulosSeleccionados = is_array($_POST['modulos']) ? $_POST['modulos'] : [];
    
    if ($controller->actualizarModulos($idPerfilSeleccionado, $modulosSeleccionados)) {
        $mensaje = "¡Configuración guardada correctamente!";
    } else {
        $error = "Error al guardar la configuración. Intente nuevamente.";
    }
}

// Obtener todos los módulos
$todosModulos = $controller->obtenerTodosModulos();

// Obtener los módulos asignados al perfil seleccionado
$modulosAsignados = $controller->obtenerModulosPorPerfil($idPerfilSeleccionado);
$idsModulosAsignados = array_column($modulosAsignados, 'Id_mod');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrar Módulos por Perfil</title>
</head>
<body>

    <h1>Asignación de Módulos por Perfil</h1>

    <!-- Menú de navegación dinámico -->
    <?php echo generarMenuDinamico(); ?>

    <!-- Mensajes de feedback -->
    <?php if ($mensaje): ?>
        <p class="mensaje-exito"><?= $mensaje ?></p>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <p class="mensaje-error"><?= $error ?></p>
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
        <table>
            <tr>
                <th>Perfil</th>
                <th>Módulo</th>
                <th>Acceso</th>
            </tr>
            <?php foreach ($todosModulos as $modulo): ?>
                <tr>
                    <td><?= htmlspecialchars($perfiles[array_search($idPerfilSeleccionado, array_column($perfiles, 'Id_p'))]['Nombre']) ?></td>
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