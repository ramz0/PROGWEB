<?php
require_once '../connection.php';

class ModuloModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodosModulos() {
        $stmt = $this->pdo->query("SELECT * FROM modulo WHERE Borrado = 0 ORDER BY Nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerModulosPorPerfil($idPerfil) {
        try {
            $sql = "SELECT m.Id_mod, m.Nombre 
                    FROM modulo m
                    JOIN mod_perfil mp ON m.Id_mod = mp.Id_mod
                    WHERE mp.Id_p = :idPerfil AND m.Borrado = 0";
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
            $this->pdo->beginTransaction();

            // Eliminar asignaciones anteriores
            $stmt = $this->pdo->prepare("DELETE FROM mod_perfil WHERE Id_p = ?");
            $stmt->execute([$idPerfil]);

            // Insertar nuevas asignaciones si hay selecciones
            if (!empty($modulosSeleccionados)) {
                // Validar que los módulos existan
                $placeholders = implode(',', array_fill(0, count($modulosSeleccionados), '?'));
                $sql = "SELECT Id_mod FROM modulo WHERE Id_mod IN ($placeholders) AND Borrado = 0";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($modulosSeleccionados);
                $modulosValidos = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Insertar solo módulos válidos
                if (!empty($modulosValidos)) {
                    $sql = "INSERT INTO mod_perfil (Id_mod, Id_p) VALUES ";
                    $values = [];
                    $params = [];
                    
                    foreach ($modulosValidos as $idModulo) {
                        $values[] = "(?, ?)";
                        $params[] = $idModulo;
                        $params[] = $idPerfil;
                    }
                    
                    $sql .= implode(',', $values);
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute($params);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al actualizar asignaciones: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodosPerfiles() {
        $stmt = $this->pdo->query("SELECT Id_p, Nombre FROM perfil WHERE Borrado = 0");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}