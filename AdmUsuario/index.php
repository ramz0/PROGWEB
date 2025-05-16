<?php
require_once __DIR__ . '/controlador.php';
require_once '../configLogin/menu_dinamico.php';

// Crear instancia del controlador
$controller = new UsuarioController();

// Procesar la solicitud y preparar los datos necesarios
$action = $_GET['action'] ?? 'mostrar';
$controller->handleRequest($action);

// Obtener los datos preparados por el controlador
$usuarios = $controller->model->obtenerUsuarios();
$perfiles = $controller->model->obtenerPerfiles();
$modoEdicion = $controller->estaEditando();
$usuarioActual = $controller->getUsuarioActual();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <title><?= $modoEdicion ? 'Editar Usuario' : 'Administración de Usuarios' ?></title>
</head>
<body>
    <div>
        <span><?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></span>
        <a href="../configLogin/logout.php">Cerrar sesión</a>
    </div>
    
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div style="color: green;"><?= $_SESSION['mensaje'] ?></div>
        <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red;"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <h1><?= $modoEdicion ? 'Editar Usuario' : 'Administración de Usuarios' ?></h1>
            <?php echo generarMenuDinamico(); ?>
            
    <section>
        <h2><?= $modoEdicion ? 'Editar Usuario' : 'Registro de Usuario' ?></h2>
        <form method="POST" action="index.php?action=<?= $modoEdicion ? 'actualizar' : 'guardar' ?>">
            <?php if ($modoEdicion): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($usuarioActual['id_u'] ?? '') ?>">
            <?php endif; ?>

            <div>
                <label>Nombre: 
                    <input type="text" name="nombre" 
                        value="<?= htmlspecialchars($usuarioActual['persona_nombre'] ?? '') ?>" 
                        maxlength="65" required>
                </label>
            </div>

            <div>
                <label>Edad: 
                    <input type="number" name="edad" 
                        value="<?= htmlspecialchars($usuarioActual['edad'] ?? '') ?>" 
                        required>
                </label>
            </div>

            <div>
                <label>Nick: 
                    <input type="text" name="nick" 
                        value="<?= htmlspecialchars($usuarioActual['nick'] ?? '') ?>" 
                        maxlength="20" required>
                </label>
            </div>

            <div>
                <label>Contraseña: 
                    <input type="password" name="pwd" 
                        value="<?= $modoEdicion ? htmlspecialchars($usuarioActual['Pwd'] ?? '') : '' ?>" 
                        maxlength="8" required>
                </label>
            </div>

            <div>
                <label>Estado: 
                    <select name="borrado">
                        <option value="0" <?= isset($usuarioActual['estado']) && $usuarioActual['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="1" <?= isset($usuarioActual['estado']) && $usuarioActual['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </label>
            </div>

            <div>
                <label>Perfil: 
                    <select name="id_p" required>
                        <?php foreach ($perfiles as $perfil): ?>
                            <option value="<?= $perfil['Id_p'] ?>" 
                                <?= isset($usuarioActual['perfil_nombre']) && $usuarioActual['perfil_nombre'] == $perfil['Nombre'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($perfil['Nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <button type="submit"><?= $modoEdicion ? 'Actualizar' : 'Guardar' ?></button>
        </form>
        </section>
        
        <?php if (!$modoEdicion): ?>
            <section>
                <h2>Lista de Usuarios</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Nick</th>
                    <th>Contraseña</th>
                    <th>Estado</th>
                    <th>Perfil</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id_u']) ?></td>
                    <td><?= htmlspecialchars($usuario['persona_nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['edad']) ?></td>
                    <td><?= htmlspecialchars($usuario['nick']) ?></td>
                    <td><?= htmlspecialchars($usuario['Pwd']) ?></td>
                    <td><?= htmlspecialchars($usuario['estado']) ?></td>
                    <td><?= htmlspecialchars($usuario['perfil_nombre'] ?? 'Desconocido') ?></td>
                    <td>
                        <a href="index.php?action=editar&id=<?= $usuario['id_u'] ?>">Editar</a> | 
                        <a href="index.php?action=eliminar&id=<?= $usuario['id_u'] ?>" 
                        onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <?php endif; ?>
</body>
</html>