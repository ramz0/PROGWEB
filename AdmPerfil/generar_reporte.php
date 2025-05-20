<?php
require_once __DIR__ . '/controlador/controlador.php';

$tipo = $_GET['tipo'] ?? '';
$action = $_GET['action'] ?? 'download';
$idUsuario = $_GET['id'] ?? 0;

$controlador = new Controlador();

switch ($tipo) {
    case 'usuario':
        if ($idUsuario > 0) {
            $controlador->generarPDF($idUsuario, $action);
        } else {
            header('Location: index.php?error=missing_id');
        }
        break;
        
    case 'perfiles':
        $controlador->generarReportePerfiles($action);
        break;
        
    default:
        header('Location: index.php');
        break;
}