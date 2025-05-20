<?php
require_once __DIR__ . '/controlador/controlador.php';

// Obtener el ID de usuario
$idUsuario = 1; // Puedes cambiarlo por $_GET['id'] si lo necesitas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdmPerfil - Generador de PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            width: 200px;
            padding: 10px;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-download {
            background-color: #4CAF50;
        }
        .btn-download:hover {
            background-color: #45a049;
        }
        .btn-preview {
            background-color: #2196F3;
        }
        .btn-preview:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>AdmPerfil</h1>
        <p>Bienvenido al generador de mensajes personalizados en PDF</p>
        
        <div class="btn-container">
            <!-- Botón para descargar PDF -->
            <a href="generar_pdf.php?id=<?php echo $idUsuario; ?>&action=download" class="btn btn-download">Graficar (Descargar)</a>
            
            <!-- Botón para vista previa -->
            <a href="generar_pdf.php?id=<?php echo $idUsuario; ?>&action=preview" target="_blank" class="btn btn-preview">Vista Previa</a>
        </div>
    </div>
</body>
</html>