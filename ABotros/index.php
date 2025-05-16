<!DOCTYPE html>
<html lang="es-MX">
<head>
  <meta charset="UTF-8">
  <title>Práctica</title>
</head>
<body>
  <h1 class="flx-center titulo1">Seguimiento de Egresados y Bolsa de Trabajo</h1>
  <h2>Inicia sesión para comenzar</h2>

  <?php if (isset($error)): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form id="formLogin" action="guardar.php" method="POST">
    <label for="user">Nombre de usuario</label>
    <input type="text" id="user" name="user" required pattern="[a-zA-Z]+" title="Solo letras permitidas">

    <label for="pwd">Contraseña</label>
    <input type="password" id="pwd" name="pwd" required>

    <button type="submit">Iniciar sesión</button>
    <p>¿Eres empleador? <a href="#">Regístrate</a></p>
  </form>
</body>
</html>