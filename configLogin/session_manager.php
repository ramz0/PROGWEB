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
            'login_time' => time()
        ];
    }

    public static function getCurrentUser() {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['nombre'] ?? null,
            'username' => $_SESSION['username'] ?? null,
        ];
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
}
?>