<?php
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../configLogin/session_manager.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?error=Método no permitido');
    exit();
}

// Obtener y sanitizar datos del formulario
$username = trim($_POST['user'] ?? '');
$password = $_POST['pwd'] ?? '';

// Validaciones básicas
if (empty($username) || empty($password)) {
    header('Location: ../index.php?error=Usuario y contraseña son requeridos');
    exit();
}

try {
    $pdo = DatabaseConnection::getInstance()->getConnection();
    
    // Consulta optimizada usando la vista
    $stmt = $pdo->prepare("
        SELECT * FROM vista_usuario_completo 
        WHERE nick = ? AND estado = 'Activo'
        LIMIT 1
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        header('Location: ../index.php?error=Usuario o contraseña incorrectos');
        exit();
    }
    
    // Verificación simple de contraseña (sin hashing)
    if ($password !== $user['Pwd']) {
        header('Location: ../index.php?error=Usuario o contraseña incorrectos');
        exit();
    }
    
    // Mapear nombres de perfil a IDs (puedes hacer esto en una tabla si prefieres)
    $perfilesIds = [
        'administrador' => 1002,
        'profesor' => 1003,
        'estudiante' => 1001
    ];
    
    // Preparar datos para la sesión
    $userData = [
        'Id_u' => $user['id_u'],
        'Nombre' => $user['persona_nombre'],
        'Username' => $user['nick'],
        'PerfilNombre' => $user['perfil_nombre'],
        'Id_p' => $perfilesIds[strtolower($user['perfil_nombre'])] ?? 1001,
        'Edad' => $user['edad']
    ];
    
    // Iniciar sesión
    SessionManager::loginUser($userData);
    
    // Redirigir según perfil
    switch (strtolower($user['perfil_nombre'])) {
        case 'administrador':
            header('Location: ../AdmUsuario/index.php');
            break;
        case 'profesor':
            header('Location: ../profesor/dashboard.php');
            break;
        case 'estudiante':
        default:
            header('Location: ../estudiante/inicio.php');
            break;
    }
    exit();
    
} catch (PDOException $e) {
    error_log('Error de autenticación: ' . $e->getMessage());
    header('Location: ../index.php?error=Error en el sistema. Intente más tarde.');
    exit();
}