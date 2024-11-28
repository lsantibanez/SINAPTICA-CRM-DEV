<?php

include __DIR__.'/../../../class/Cargas.php';
header('Content-Type: application/json; charset=utf-8');
//$data = json_decode(file_get_contents('php://input'), true);
$data = json_decode(file_get_contents('php://input'), true);
$cargas = new Cargas();
$resultados = $cargas->segmentPortfolios($data['id']);
echo json_encode($resultados, JSON_UNESCAPED_UNICODE);
?>