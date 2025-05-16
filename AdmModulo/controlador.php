<?php
require_once 'modelo.php';

class ModuloController {
    private $model;

    public function __construct() {
        $pdo = getDBConnection();
        $this->model = new ModuloModel($pdo);
    }

    public function obtenerTodosModulos() {
        return $this->model->obtenerTodosModulos();
    }

    public function obtenerModulosPorPerfil($idPerfil) {
        if (!filter_var($idPerfil, FILTER_VALIDATE_INT)) {
            return [];
        }
        return $this->model->obtenerModulosPorPerfil($idPerfil);
    }

    public function actualizarModulos($idPerfil, $modulosSeleccionados) {
        $result = $this->model->actualizarAsignaciones($idPerfil, $modulosSeleccionados);
        
        // Si la actualización fue exitosa y es el perfil del usuario actual
        if ($result && SessionManager::getCurrentProfileId() == $idPerfil) {
            SessionManager::reloadUserModules();
        }
        
        return $result;
    }

    public function obtenerTodosPerfiles() {
        return $this->model->obtenerTodosPerfiles();
    }

    // Eliminé los métodos relacionados con usuario_modulo ya que no corresponden a esta clase
    // Esos deberían estar en un controlador específico para gestión de usuarios
}