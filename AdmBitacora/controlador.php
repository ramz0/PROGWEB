<?php
require_once 'modelo.php';

class BitacoraController {
    private $model;

    public function __construct() {
        $this->model = new BitacoraModel();
    }

    // Obtener registros para la vista
    public function obtenerRegistros($nombre = null) {
        return $this->model->obtenerRegistros($nombre);
    }

    public function registrarAccion($idUsuario, $accion) {
        return $this->model->registrarAccion($idUsuario, $accion);
    }
}
?>