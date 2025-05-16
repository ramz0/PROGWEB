<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db = new Conexion("usuario");

        $data = [
            "Id_u" => $_POST["id_u"],
            "Nombre" => $_POST["nombre"],
            "Edad" => $_POST["edad"],
            "Nick" => $_POST["nick"],
            "Pwd" => $_POST["pwd"],
            "Borrado" => $_POST["borrado"],
            "Id_p" => $_POST["id_p"]
        ];

        $insertId = $db->create($data);
        if ($insertId !== false && $insertId !== 0) {
            header("Location: insercion.php");
            exit();
        } else {
            echo "Error al insertar el usuario.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
