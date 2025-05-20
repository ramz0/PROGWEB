<?php
require_once __DIR__ . '/controlador/controlador.php';

$idUsuario = $_GET['id'] ?? 1;
$action = $_GET['action'] ?? 'download'; // 'download' o 'preview'

$controlador = new Controlador();

// Llamamos al método modificado que acepta el parámetro de acción
$controlador->generarPDF($idUsuario, $action);
?>