<?php
require_once 'session_manager.php';
require_once '../connection.php';

function generarMenuDinamico() {
    SessionManager::startSession();
    $usuarioLogueado = SessionManager::getCurrentUser();
    
    if (!isset($usuarioLogueado['id'])) {
        return '<div class="user-info">No hay usuario logueado</div>';
    }

    $pdo = getDBConnection();
    
    // Obtener el perfil del usuario logueado
    $stmt = $pdo->prepare("SELECT p.Id_p, p.Nombre FROM usuario u JOIN perfil p ON u.Id_p = p.Id_p WHERE u.Id_u = ?");
    $stmt->execute([$usuarioLogueado['id']]);
    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
    $idPerfil = $perfil['Id_p'] ?? null;
    $nombrePerfil = $perfil['Nombre'] ?? 'Sin perfil';

    if (!$idPerfil) {
        return '<div class="user-info">No se encontró el perfil del usuario</div>';
    }
    
    // Obtener módulos habilitados para el perfil del usuario logueado
    $stmt = $pdo->prepare("
        SELECT m.Id_mod, m.Nombre AS Nombre_mod, m.URL 
        FROM modulo m
        JOIN mod_perfil mp ON m.Id_mod = mp.Id_mod
        WHERE mp.Id_p = ?
        ORDER BY m.Nombre
    ");
    $stmt->execute([$idPerfil]);
    $modulosHabilitados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Generar HTML del menú
    $html = '<div class="user-info">';
    $html .= 'Usuario: <strong>'.htmlspecialchars($usuarioLogueado['name'] ?? 'Invitado').'</strong> | ';
    $html .= 'Perfil: <strong>'.htmlspecialchars($nombrePerfil).'</strong>';
    $html .= '</div>';
    
    $html .= '<nav class="menu-dinamico">';
    if (!empty($modulosHabilitados)) {
        foreach ($modulosHabilitados as $modulo) {
            $html .= '<a href="/PROGWEB/'.htmlspecialchars($modulo['URL']).'">'.htmlspecialchars($modulo['Nombre_mod']).'</a> ';
        }
    } else {
        $html .= '<p>No tienes módulos asignados</p>';
    }
    $html .= '</nav>';
    
    return $html;
}
?>