<?php
require_once '../connection.php';

class UsuarioModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function obtenerModulosPorPerfil($idPerfil) {
        try {
            $sql = "
                SELECT m.Id_mod, m.Nombre 
                FROM modulo m
                JOIN mod_perfil mp ON m.Id_mod = mp.Id_mod
                WHERE mp.Id_p = :idPerfil
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idPerfil', $idPerfil, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener módulos por perfil: " . $e->getMessage());
            return [];
        }
    }

    public function actualizarAsignaciones($idPerfil, $modulosSeleccionados) {
        try {
            $this->pdo->beginTransaction(); // Usar $this->pdo en lugar de $this->db

            // Eliminar asignaciones anteriores
            $stmt = $this->pdo->prepare("DELETE FROM mod_perfil WHERE Id_p = ?");
            $stmt->execute([$idPerfil]);

            // Insertar nuevas asignaciones
            if (!empty($modulosSeleccionados)) {
                $sql = "INSERT INTO mod_perfil (Id_mod, Id_p) VALUES ";
                $values = [];
                $params = [];

                foreach ($modulosSeleccionados as $idModulo) {
                    $values[] = "(?, ?)";
                    $params[] = $idModulo;
                    $params[] = $idPerfil;
                }

                $sql .= implode(',', $values);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al actualizar asignaciones: " . $e->getMessage());
            return false;
        }
    }
}
?>