<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
include("conexion.php");
$conexion->set_charset("utf8mb4");

$resultado = $conexion->query("SELECT * FROM productos");
$lista = [];
while($fila = $resultado->fetch_assoc()){
    $lista[] = $fila;
}
// JSON_UNESCAPED_UNICODE es para que se vean bien las tildes y ñ
echo json_encode($lista, JSON_UNESCAPED_UNICODE);
?>