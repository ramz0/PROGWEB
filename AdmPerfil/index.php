<?php
require_once __DIR__ . '/controlador/controlador.php';

$controlador = new Controlador();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AdmPerfil - Reportes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .panel {
            background: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            flex: 1;
        }
        .btn-preview {
            background: #2196F3;
        }
        .btn-report {
            background: #FF9800;
        }
    </style>
</head>
<body>
    <h1>Sistema de Reportes</h1>
    
    <div class="panel">
        <h2>Reporte de Usuario</h2>
        <form action="generar_reporte.php" method="get">
            <input type="hidden" name="tipo" value="usuario">
            <label>ID Usuario: <input type="number" name="id" min="1" required></label>
            <div class="btn-group">
                <button type="submit" name="action" value="preview" class="btn btn-preview">Vista Previa</button>
                <button type="submit" name="action" value="download" class="btn">Descargar PDF</button>
            </div>
        </form>
    </div>
    
    <div class="panel">
        <h2>Reporte General de Perfiles</h2>
        <form action="generar_reporte.php" method="get">
            <input type="hidden" name="tipo" value="perfiles">
            <div class="btn-group">
                <button type="submit" name="action" value="preview" class="btn btn-preview">Vista Previa</button>
                <button type="submit" name="action" value="download" class="btn">Descargar PDF</button>
            </div>
        </form>
    </div>
</body>
</html>