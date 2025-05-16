<?php
class SessionManager {
    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function loginUser($userData) {
        self::startSession();
        
        $_SESSION['user'] = [
            'id' => $userData['Id_u'],
            'name' => $userData['Nombre'],
            'username' => $userData['Username'],
            'profile_name' => $userData['PerfilNombre'],
            'profile_id' => $userData['Id_p'],
            'age' => $userData['Edad'] ?? null,
            'login_time' => time(),
            'modules' => self::loadUserModules($userData['Id_p'])
        ];
    }

    public static function reloadUserModules() {
        if (self::isLoggedIn()) {
            $_SESSION['user']['modules'] = self::loadUserModules($_SESSION['user']['profile_id']);
        }
    }

    private static function loadUserModules($profileId) {
        require_once __DIR__ . '/../connection.php';
        $pdo = DatabaseConnection::getInstance()->getConnection();
        
        $stmt = $pdo->prepare("
            SELECT m.Nombre 
            FROM modulo m
            JOIN mod_perfil mp ON m.Id_mod = mp.Id_mod
            WHERE mp.Id_p = :profileId AND m.Borrado = 0
            ORDER BY m.Nombre
        ");
        $stmt->bindParam(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getCurrentUser() {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function logout() {
        self::startSession();
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['user']);
    }

    public static function hasPermission($moduleName) {
        return self::isLoggedIn() && in_array($moduleName, $_SESSION['user']['modules'] ?? []);
    }

    public static function getCurrentProfileId() {
        return self::getCurrentUser()['profile_id'] ?? null;
    }

    public static function getCurrentProfileName() {
        return self::getCurrentUser()['profile_name'] ?? null;
    }

    public static function isAdmin() {
        return self::getCurrentProfileName() === 'administrador';
    }

    public static function isProfessor() {
        return self::getCurrentProfileName() === 'profesor';
    }

    public static function isStudent() {
        return self::getCurrentProfileName() === 'estudiante';
    }
}