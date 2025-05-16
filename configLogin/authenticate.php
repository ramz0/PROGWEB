<?php
require_once __DIR__ . '/../connection.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?error=Método no permitido');
    exit();
}

// Obtener datos del formulario
$username = trim($_POST['user'] ?? '');
$password = $_POST['pwd'] ?? '';

// Validaciones básicas
if (empty($username) || empty($password)) {
    header('Location: ../index.php?error=Usuario y contraseña son requeridos');
    exit();
}

try {
    $pdo = DatabaseConnection::getInstance()->getConnection();
    
    // Buscar usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Nick = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        header('Location: ../index.php?error=Usuario o contraseña incorrectos');
        exit();
    }
    
    // Verificar contraseña
    if (strpos($user['Pwd'], '$2y$10$') === 0) {
        // Contraseña hasheada
        if (!password_verify($password, $user['Pwd'])) {
            header('Location: ../index.php?error=Usuario o contraseña incorrectos');
            exit();
        }
    } else {
        // Contraseña en texto plano (no seguro)
        if ($password !== $user['Pwd']) {
            header('Location: ../index.php?error=Usuario o contraseña incorrectos');
            exit();
        }
    }
    
    // Iniciar sesión
    session_start();
    $_SESSION['user_id'] = $user['Id_u'];
    $_SESSION['username'] = $user['Nick'];
    $_SESSION['nombre'] = $user['Nombre'];  // DEBO RECORDAR QUE ESTO YA ESTA EN LA TABLA [PERSONA]
    
    // Redirigir al panel de administración
    header('Location: ../AdmUsuario/index.php');
    exit();
    
} catch (PDOException $e) {
    error_log('Error de autenticación: ' . $e->getMessage());
    header('Location: ../index.php?error=Error en el sistema. Intente más tarde.');
    exit();
}