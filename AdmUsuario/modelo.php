<?php
require_once __DIR__ . '/../connection.php';

class UsuarioModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function obtenerUltimoIdBitacora() {
        $stmt = $this->pdo->query("SELECT MAX(id_b) as max_id FROM bitacora");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_id'] ?? 0;
    }

    public function registrarAccionBitacora($accion) {
        session_start(); // Asegúrate de iniciar la sesión si no está iniciada
        $usuarioId = $_SESSION['user_id'] ?? null; // Obtén el ID del usuario de la sesión

        if (!$usuarioId) {
            throw new Exception("No se encontró el ID del usuario en la sesión.");
        }

        $nuevoId = $this->obtenerUltimoIdBitacora() + 1;

        $sql = "INSERT INTO bitacora (id_b, accion, id_u, fecha, hora) VALUES (:id_b, :accion, :id_u, CURDATE(), CURTIME())";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id_b' => $nuevoId,
            ':accion' => $accion,
            ':id_u' => $usuarioId
        ]);
    }

    public function obtenerUsuarios() {
        $stmt = $this->pdo->query("
            SELECT * FROM vista_usuario_completo
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuario($id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM vista_usuario_completo
            WHERE id_u = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimoId() {
        $stmt = $this->pdo->query("SELECT MAX(Id_u) as max_id FROM usuario");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_id'] ?? 0;
    }

    public function obtenerPerfiles() {
        $stmt = $this->pdo->query("SELECT * FROM perfil");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearUsuario($datos) {
        try {
            $sql = "CALL sp_crear_usuario(:nombre, :edad, :nick, :pwd, :borrado, :id_perfil)";
            $stmt = $this->pdo->prepare($sql);

            // No se realiza el hash de la contraseña
            $stmt->bindParam(':nombre',    $datos['nombre']);
            $stmt->bindParam(':edad',      $datos['edad']);
            $stmt->bindParam(':nick',      $datos['nick']);
            $stmt->bindParam(':pwd',       $datos['pwd']); // Contraseña sin hash
            $stmt->bindParam(':borrado',   $datos['borrado']);
            $stmt->bindParam(':id_perfil', $datos['id_perfil']);

            $stmt->execute();

            while ($stmt->nextRowset()) { /* consume todos los resultsets */ }

            $result = $this->pdo->query("SELECT LAST_INSERT_ID() AS id");
            $data = $result->fetch(PDO::FETCH_ASSOC);

            return $data['id'] ?? false;

        } catch (PDOException $e) {
            echo "⚠️ Error en crearUsuario(): " . $e->getMessage();
            return false;
        }
    }
    
    public function actualizarUsuario($datos) {
        try {
            var_dump($datos); // Depuración
            $sql = "CALL sp_actualizar_usuario(:id_usuario, :nombre, :edad, :nick, :pwd, :borrado, :id_perfil)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id_usuario', $datos['id_usuario']);
            $stmt->bindParam(':nombre',     $datos['nombre']);
            $stmt->bindParam(':edad',       $datos['edad']);
            $stmt->bindParam(':nick',       $datos['nick']);
            $stmt->bindParam(':pwd',        $datos['pwd']); 
            $stmt->bindParam(':borrado',    $datos['borrado']);
            $stmt->bindParam(':id_perfil',  $datos['id_perfil']);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "⚠️ Error en actualizarUsuario(): " . $e->getMessage();
            return false;
        }
    }

    public function eliminarUsuario($id_usuario) {
        try {
            $sql = "CALL sp_eliminar_usuario(:id_usuario)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id_usuario', $id_usuario);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "⚠️ Error en eliminarUsuario(): " . $e->getMessage();
            return false;
        }
    }

}