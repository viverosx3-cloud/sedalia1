<?php
$servidor   = "localhost";
$usuario    = "root";
$password   = "";
$base_datos = "sedalia1_db"; // <-- Cambia "sedalia1" por "sedalia1_db"

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}
?>