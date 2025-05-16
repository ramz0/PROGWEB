<?php

require_once __DIR__ . '/modelo.php';

class UsuarioController {
    public $model;
    private $modoEdicion = false;
    private $usuarioActual = null;

    public function __construct() {
        $this->model = new UsuarioModel();
    }

    public function handleRequest($action) {
        switch ($action) {
            case 'guardar':
                $this->guardarUsuario();
                break;
            case 'editar':
                $this->prepararEdicion();
                break;
            case 'actualizar':
                $this->actualizarUsuario();
                break;
            case 'eliminar':
                $this->eliminarUsuario();
                break;
            case 'mostrar':
            default:
                // No hace nada, los datos ya están preparados
                break;
        }
    }

    private function prepararEdicion() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->modoEdicion = true;
            $this->usuarioActual = $this->model->obtenerUsuario($id);
            
            // Asegurar que los datos de persona están disponibles
            if ($this->usuarioActual && !isset($this->usuarioActual['persona_nombre'])) {
                $_SESSION['error'] = 'No se encontraron los datos completos del usuario';
                header('Location: index.php');
                exit();
            }
        }
    }

    private function guardarUsuario() {
        try {
            var_dump($_POST); // Depuración
            $datosUsuario = [
                'nombre' => $_POST['nombre'],
                'edad' => $_POST['edad'],
                'nick' => $_POST['nick'],
                'pwd' => $_POST['pwd'],
                'borrado' => $_POST['borrado'],
                'id_perfil' => $_POST['id_p']
            ];
    
            $resultado = $this->model->crearUsuario($datosUsuario);
    
            if ($resultado) {
                $_SESSION['mensaje'] = 'Usuario creado exitosamente.';
            } else {
                $_SESSION['error'] = 'No se pudo crear el usuario.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el usuario: ' . $e->getMessage();
        }
    
        header('Location: index.php');
        exit;
    }

    private function actualizarUsuario() {
        try {
            var_dump($_POST); // Depuración
            $datosUsuario = [
                'id_usuario' => $_POST['id'],
                'nombre' => $_POST['nombre'],
                'edad' => $_POST['edad'],
                'nick' => $_POST['nick'],
                'pwd' => $_POST['pwd'], // Puede ser NULL si no se actualiza
                'borrado' => $_POST['borrado'],
                'id_perfil' => $_POST['id_p']
            ];
    
            $resultado = $this->model->actualizarUsuario($datosUsuario);
    
            if ($resultado) {
                $_SESSION['mensaje'] = 'Usuario actualizado exitosamente.';
            } else {
                $_SESSION['error'] = 'No se pudo actualizar el usuario.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el usuario: ' . $e->getMessage();
        }
    
        header('Location: index.php');
        exit;
    }

    private function eliminarUsuario() {
        try {
            $id_usuario = $_GET['id'];
    
            $resultado = $this->model->eliminarUsuario($id_usuario);
    
            if ($resultado) {
                $_SESSION['mensaje'] = 'Usuario eliminado exitosamente.';
            } else {
                $_SESSION['error'] = 'No se pudo eliminar el usuario.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el usuario: ' . $e->getMessage();
        }
    
        header('Location: index.php');
        exit;
    }

    private function procesarDatosFormulario() {
        return [
            'nombre' => filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING),
            'edad' => filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]),
            'nick' => filter_input(INPUT_POST, 'nick', FILTER_SANITIZE_STRING),
            'pwd' => $_POST['pwd'], // No filtrar para permitir todos los caracteres
            'borrado' => filter_input(INPUT_POST, 'borrado', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]),
            'id_p' => filter_input(INPUT_POST, 'id_p', FILTER_VALIDATE_INT)
        ];
    }

    public function getUsuarioActual() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            return $this->model->obtenerUsuario($id); // Asegúrate de que este método existe en el modelo
        }
        return null;
    }

    public function estaEditando() {
        return $this->modoEdicion;
    }

    // public function getUsuarioActual() {
    //     return $this->usuarioActual;
    // }
}