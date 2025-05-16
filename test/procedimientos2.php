<?php
require_once 'procedimientos.php';

$proc = new Procedimiento();
$datos = [
    'nombre'    => 'Carlos',
    'edad'      => 25,
    'nick'      => 'carlos',
    'pwd'       => '123',
    'borrado'   => 0,
    'id_perfil' => 1001
];

$nuevoId = $proc->crearUsuario($datos);

if ($nuevoId) {
    echo "✅ Usuario creado con ID: $nuevoId";
} else {
    echo "❌ No se pudo crear el usuario.";
}
