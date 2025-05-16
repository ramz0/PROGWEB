<?php
include 'config.inc';
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'];
    $pwd = $_POST['pwd'];

    if(preg_match("/^[a-zA-Z]+$/", $user)) {
        try {
            $db = new Conexion("usuario");
            
            $sql = "SELECT * FROM usuario WHERE Nombre = :user AND Pwd = :pwd";
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':pwd', $pwd);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                session_start();
                $_SESSION['user'] = $user;
                
                header("Location: insercion.php");
                exit();
            } else 
                echo "Usuario o contraseña incorrectos.";
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    } else 
        echo "El usuario $user contiene otros caracteres que no son alfabéticos.";
}
?>