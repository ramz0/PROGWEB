<?php
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
  <meta charset="UTF-8">
  <title>Sistema de Seguimiento</title>
</head>
<body>
  <header>
    <h1>Seguimiento de Egresados y Bolsa de Trabajo</h1>
    <h2>Inicia sesión para comenzar</h2>
  </header>

  <main>
    <?php if ($error): ?>
      <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="formLogin" action="configLogin/authenticate.php" method="POST">
        <label for="user">Nick </label>
        <input type="text" id="user" name="user" required>

        <label for="pwd">Contraseña</label>
        <input type="password" id="pwd" name="pwd" required>

        <button type="submit">Iniciar sesión</button>
    </form>

    <div class="register-link">
      <p>¿Eres empleador? <a href="#">Regístrate</a></p>
    </div>
  </main>
</body>
</html>