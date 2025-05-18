<?php
require_once '../connection.php';

class BitacoraModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function obtenerRegistros($nombre = null) {
        try {
            $sql = "
                SELECT * FROM vista_bitacora
            ";

            // Agregar filtro por nombre si se proporciona
            if ($nombre) 
                $sql .= " WHERE Nombre LIKE :nombre";
            

            $sql .= " ORDER BY Fecha DESC, Hora DESC";

            $stmt = $this->pdo->prepare($sql);

            // Vincular el parámetro si se proporciona un nombre
            if ($nombre) 
                $stmt->bindValue(':nombre', '%' . $nombre . '%', PDO::PARAM_STR);
            

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener registros de la bitácora: " . $e->getMessage());
            return [];
        }
    }

    public function registrarAccion($idUsuario, $accion) {
        try {
            $sql = "INSERT INTO bitacora (fecha, hora, accion, id_u) 
                    VALUES (CURDATE(), CURTIME(), :accion, :idUsuario)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':accion', $accion, PDO::PARAM_STR);
            $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) { 
            error_log("Error al registrar acción en bitácora: " . $e->getMessage());
            return false;
        }
    }
}
?>