<?php
require_once __DIR__ . '/../connection.php';

class Procedimiento {
    private $pdo;

    public function __construct() {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function crearUsuario($datos) {
        try {
            $sql = "CALL sp_crear_usuario(:nombre, :edad, :nick, :pwd, :borrado, :id_perfil)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':nombre',    $datos['nombre']);
            $stmt->bindParam(':edad',      $datos['edad']);
            $stmt->bindParam(':nick',      $datos['nick']);
            $stmt->bindParam(':pwd',       $datos['pwd']);
            $stmt->bindParam(':borrado',   $datos['borrado']);
            $stmt->bindParam(':id_perfil', $datos['id_perfil']);

            $stmt->execute();

            // 🚨 IMPORTANTE: limpiar resultados anteriores si el SP devuelve múltiples conjuntos
            while ($stmt->nextRowset()) { /* consume todos los resultsets */ }

            // ✅ Ahora sí puedes obtener el último ID insertado
            $result = $this->pdo->query("SELECT LAST_INSERT_ID() AS id");
            $data = $result->fetch(PDO::FETCH_ASSOC);

            return $data['id'] ?? false;

        } catch (PDOException $e) {
            echo "⚠️ Error en crearUsuario(): " . $e->getMessage();
            return false;
        }
    }
}
