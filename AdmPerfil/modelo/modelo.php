<?php
class Modelo {
    public function obtenerDatosUsuario($idUsuario) {
        // Simulamos datos (en un caso real, aquí iría tu consulta a BD)
        return [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje_personalizado' => 'Este es un mensaje especial para ti.'
        ];
    }
}
?>