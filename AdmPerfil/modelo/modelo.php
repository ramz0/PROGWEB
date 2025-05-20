<?php
class Modelo {
    private $db;

    public function __construct() {
        // Ajusta los datos de conexión según tu configuración
        $host = 'localhost';
        $dbname = 'progweb';
        $user = 'root';
        $pass = '';

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function obtenerDatosUsuario($idUsuario) {
        // Simulamos datos (en un caso real, aquí iría tu consulta a BD)
        return [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje_personalizado' => 'Este es un mensaje especial para ti.'
        ];
    }

    public function obtenerEstadisticasPerfiles() {
        $query = "CALL sp_obtener_estadisticas_perfiles()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Define tus colores personalizados (tantos como perfiles)
        $colores = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
    
        // Asigna un color a cada perfil
        foreach ($resultados as $i => &$perfil) {
            $perfil['color'] = $colores[$i % count($colores)];
        }
        return $resultados;
    }
}
?>