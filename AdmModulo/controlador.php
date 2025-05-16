<?php
require_once '../connection.php';
require_once 'modelo.php'; // Asegúrate de incluir el archivo del modelo

class ModuloController {
    private $pdo;
    private $model; // Declarar la propiedad $model

    public function __construct() {
        $this->pdo = getDBConnection();
        $this->model = new UsuarioModel(); // Inicializar el modelo
    }

    public function obtenerTodosModulos() {
        $stmt = $this->pdo->query("SELECT * FROM modulo WHERE Borrado = 0 ORDER BY Nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerModulosAsignados($idUsuario) {
        $stmt = $this->pdo->prepare("SELECT m.* FROM modulo m 
                                   JOIN usuario_modulo um ON m.Id_mod = um.id_mod 
                                   WHERE um.id_u = ? AND um.acceso = 1
                                   ORDER BY m.Nombre");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarModulosUsuario($idUsuario, $modulosSeleccionados) {
        try {
            $this->pdo->beginTransaction();
            
            // 1. Desactivar todos los módulos para este usuario
            $stmt = $this->pdo->prepare("UPDATE usuario_modulo SET acceso = 0 WHERE id_u = ?");
            $stmt->execute([$idUsuario]);
            
            // 2. Activar solo los módulos seleccionados
            if (!empty($modulosSeleccionados)) {
                $placeholders = implode(',', array_fill(0, count($modulosSeleccionados), '?'));
                
                // Primero eliminamos registros existentes para evitar duplicados
                $stmt = $this->pdo->prepare("DELETE FROM usuario_modulo 
                                            WHERE id_u = ? AND id_mod IN ($placeholders)");
                $stmt->execute(array_merge([$idUsuario], $modulosSeleccionados));
                
                // Insertamos nuevos accesos
                $sql = "INSERT INTO usuario_modulo (id_u, id_mod, acceso) VALUES ";
                $values = [];
                $params = [];
                
                foreach ($modulosSeleccionados as $idModulo) {
                    $values[] = "(?, ?, 1)";
                    $params[] = $idUsuario;
                    $params[] = $idModulo;
                }
                
                $sql .= implode(',', $values);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
            }
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al actualizar módulos: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerModulosPorUsuario($idUsuario) {
        // Obtener el perfil del usuario
        $sql = "SELECT Id_p FROM usuario WHERE Id_u = :idUsuario";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC)['Id_p'] ?? null;

        if ($perfil) {
            // Obtener módulos permitidos para el perfil
            return $this->obtenerModulosPorPerfil($perfil);
        }

        return [];
    }

    public function obtenerModulosPorPerfil($idPerfil) {
        return $this->model->obtenerModulosPorPerfil($idPerfil);
    }

    public function actualizarModulos($idPerfil, $modulosSeleccionados) {
        return $this->model->actualizarAsignaciones($idPerfil, $modulosSeleccionados);
    }
}
?>