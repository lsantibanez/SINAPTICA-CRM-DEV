<?php
include __DIR__.'/../../../class/Cargas.php';
$cargas = new Cargas();
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: application/json; charset=utf-8');
echo json_encode($cargas->loadData($data['id']));
?>