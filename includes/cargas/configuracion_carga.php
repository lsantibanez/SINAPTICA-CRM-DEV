<?php
include_once '../../class/Cargas.php';
$cargas= new Cargas();
$data = json_decode(file_get_contents('php://input'), true);
$id = (int) trim($data['id']);
header('Content-type: application/json; charset=utf-8');
echo json_encode($cargas->getConfiguracion($id));
?>