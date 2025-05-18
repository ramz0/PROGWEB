<?php
require_once __DIR__ . '/modelo.php';
require_once '../AdmBitacora/controlador.php';

class UsuarioController {
    public $model;
    private $modoEdicion = false;
    private $usuarioActual = null;
    private $bitacoraController;

    public function __construct() {
        $this->model = new UsuarioModel();
        $this->bitacoraController = new BitacoraController();
        
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Recordar que este metodo es Temporal.
    public function registrarAccionBitacora($accion) {
        try {
            // 1. Obtener usuario actual
            $currentUser = SessionManager::getCurrentUser();
            if (!$currentUser) {
                throw new Exception("No hay usuario autenticado");
            }
    
            // 2. Obtener ID de usuario (compatible con varias estructuras de sesión)
            $usuarioId = $currentUser['id'] ?? $currentUser['Id_u'] ?? null;
            if (!$usuarioId) {
                throw new Exception("ID de usuario no disponible en sesión");
            }
    
            // 3. Verificar que el usuario existe
            $stmt = $this->pdo->prepare("SELECT 1 FROM usuario WHERE Id_u = ?");
            $stmt->execute([$usuarioId]);
            if (!$stmt->fetch()) {
                throw new Exception("Usuario con ID $usuarioId no existe");
            }
    
            // 4. Preparar e insertar registro
            $accion = substr($accion, 0, 50); // Ajustar al límite del campo
            $sql = "INSERT INTO bitacora (fecha, hora, accion, id_u) 
                    VALUES (CURDATE(), CURTIME(), ?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt->execute([$accion, $usuarioId])) {
                $error = $stmt->errorInfo();
                throw new Exception("Error SQL: " . $error[2]);
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error en bitácora: " . $e->getMessage());
            return false;
        }
    }

    //---------------------------------------

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
            
            if ($this->usuarioActual && !isset($this->usuarioActual['persona_nombre'])) {
                $_SESSION['error'] = 'No se encontraron los datos completos del usuario';
                header('Location: index.php');
                exit();
            }
        }
    }

    private function guardarUsuario() {
        try {
            $datosUsuario = $this->procesarDatosFormulario();
            $nuevoId = $this->model->crearUsuario($datosUsuario);
            
            if ($nuevoId) {
                // Registrar en bitácora
                $this->model->registrarAccionBitacora(
                    "Inserción de usuario \t [ ID: $nuevoId \tNOMBRE: " . $datosUsuario['nombre'] . " ]"
                );
                
                $_SESSION['mensaje'] = 'Usuario creado exitosamente.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
        
        header('Location: index.php');
        exit;
    }

    private function actualizarUsuario() {
        try {
            $datosUsuario = $this->procesarDatosFormulario();
            $idUsuario = $_POST['id'] ?? 0;
            
            if (empty($idUsuario)) 
                throw new Exception("ID de usuario no proporcionado");
            
    
            $resultado = $this->model->actualizarUsuario($datosUsuario);
    
            if ($resultado) {
                // Registrar en bitácora
                $this->bitacoraController->registrarAccion(
                    $_SESSION['user_id'] ?? 0,
                    "Actualización de usuario \t [ ID: $idUsuario \tNOMBRE: " . $datosUsuario['nombre'] . " ]"
                );
                
                $_SESSION['mensaje'] = 'Usuario actualizado exitosamente.';
            } 
            else 
                $_SESSION['error'] = 'No se pudo actualizar el usuario.';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el usuario: ' . $e->getMessage();
        }
    
        header('Location: index.php');
        exit;
    }

    private function eliminarUsuario() {
        try {
            $id_usuario = $_GET['id'] ?? 0;
            
            if (empty($id_usuario)) 
                throw new Exception("ID de usuario no proporcionado");
               
            // Obtener datos del usuario antes de eliminarlo para el registro
            $usuario = $this->model->obtenerUsuario($id_usuario);
            $nombreUsuario = $usuario['persona_nombre'] ?? 'Desconocido';
    
            $resultado = $this->model->eliminarUsuario($id_usuario);
    
            if ($resultado) {
                // Registrar en bitácora
                $this->bitacoraController->registrarAccion(
                    $_SESSION['user_id'] ?? 0,
                    "Eliminación de usuario [ ID: $id_usuario \tNOMBRE: $nombreUsuario" . " ]"
                );
                
                $_SESSION['mensaje'] = 'Usuario eliminado exitosamente.';
            } 
            else 
                $_SESSION['error'] = 'No se pudo eliminar el usuario.';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el usuario: ' . $e->getMessage();
        }
    
        header('Location: index.php');
        exit;
    }

    private function procesarDatosFormulario() {
        return [
            'nombre' => filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING),
            'edad' => filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT, 
                     ['options' => ['min_range' => 1, 'max_range' => 120]]),
            'nick' => filter_input(INPUT_POST, 'nick', FILTER_SANITIZE_STRING),
            'pwd' => $_POST['pwd'] ?? '', // No filtrar para permitir todos los caracteres
            'borrado' => filter_input(INPUT_POST, 'borrado', FILTER_VALIDATE_INT, 
                     ['options' => ['min_range' => 0, 'max_range' => 1]]),
            'id_perfil' => filter_input(INPUT_POST, 'id_p', FILTER_VALIDATE_INT),
            'id_usuario' => filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) // Para actualización
        ];
    }

    public function getUsuarioActual() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            return $this->model->obtenerUsuario($id);
        }
        return null;
    }

    public function estaEditando() {
        return $this->modoEdicion;
    }
}