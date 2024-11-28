<?php
include_once '../../class/Consultas.php';
$consultas = new Consultas();
$data = json_decode( file_get_contents('php://input'), true);
header('Content-type: application/json; charset=utf-8');
echo json_encode($consultas->findCustomer($data));
?>