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
}
?>