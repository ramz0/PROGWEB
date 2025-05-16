<?php
require_once __DIR__ . '/../connection.php';

echo "<h1>Prueba de conexión a la base de datos</h1>";

try {
    $pdo = DatabaseConnection::getInstance()->getConnection();
    echo "<p style='color: green;'>✅ Conexión exitosa</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>

<h2>Verificar usuario por Nick y contraseña</h2>

<form method="POST">
    <label for="nick">Nick:</label>
    <input type="text" id="nick" name="nick" required><br>

    <label for="pwd">Contraseña:</label>
    <input type="password" id="pwd" name="pwd" required><br>

    <button type="submit">Verificar</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = trim($_POST['nick']);
    $pwd = $_POST['pwd'];

    // Buscar el usuario
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Nick = ? LIMIT 1");
    $stmt->execute([$nick]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<p style='color: red;'>❌ Usuario no encontrado o está marcado como borrado.</p>";
    } else {
        // Verificar contraseña (ya sea en texto plano o hasheada)
        if ((strpos($user['Pwd'], '$2y$10$') === 0 && password_verify($pwd, $user['Pwd'])) ||
            $pwd === $user['Pwd']) {
            echo "<p style='color: green;'>✅ Usuario autenticado correctamente. ID: {$user['Id_u']}, Nick: {$user['Nick']}</p>";
        } else {
            echo "<p style='color: red;'>❌ Contraseña incorrecta.</p>";
        }
    }
}
?>
