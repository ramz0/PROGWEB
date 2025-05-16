<?php
    require_once "conexion.php";

    $consultaUsuario = new Conexion("usuario");
    $consultaPerfil = new Conexion("perfil"); 

    $usuarios = $consultaUsuario->read();
    $perfiles = $consultaPerfil->read();

    $maxId = $consultaUsuario->getMaxId();
    $nextId = $maxId ? $maxId + 1 : 1;

    $perfilesMap = [];
    foreach ($perfiles as $perfil) 
        $perfilesMap[$perfil['Id_p']] = $perfil['Nombre'];
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Usuario</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="procesar.php" method="POST">
        <input type="hidden" name="id_u" value="<?= $nextId ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" maxlength="65" required><br><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required><br><br>

        <label for="nick">Nick:</label>
        <input type="text" id="nick" name="nick" maxlength="20" required><br><br>

        <label for="pwd">Contraseña:</label>
        <input type="password" id="pwd" name="pwd" maxlength="8" required><br><br>

        <label for="borrado">Borrado:</label>
        <select id="borrado" name="borrado">
            <option value="0">No</option>
            <option value="1">Sí</option>
        </select><br><br>

        <label for="id_p">ID Perfil:</label>
            <select id="id_p" name="id_p">
                <?php if (!empty($perfiles)): ?>
                    <?php foreach ($perfiles as $perfil): ?>
                        <?php $valueIdPerfil = htmlspecialchars($perfil['Id_p'] ?? 'N/A') ?>
                        <?php $valueNombrePerfil = htmlspecialchars($perfil['Nombre'] ?? 'N/A') ?>
                        <option value="<?= $valueIdPerfil ?>"><?= $valueNombrePerfil ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select><br><br>

        <button type="submit">Guardar</button>
    </form>

    <div>

        <nav class = "nav-bar">
            <h4>MENU Admininistrador</h4>
            <button type = "submit">Admininistrador Usuarios</button>
            <button type = "submit">Admininistrador Modulos</button>
            <button type = "submit">Admininistrador Bitacora</button> 
        </nav>

        <h2>Admininistrador Usuario</h2>

        </div>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Nick</th>
                    <th>Contraseña</th>
                    <th>Borrado</th>
                    <th>Perfil</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['Id_u'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($usuario['Nombre'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($usuario['Edad'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($usuario['Nick'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($usuario['Pwd'] ?? 'N/A') ?></td>
                            <td><?= $usuario['Borrado'] == 1 ? 'Sí' : 'No' ?></td>
                            <td><?= htmlspecialchars($perfilesMap[$usuario['Id_p']] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
</div>
</body>
</html>