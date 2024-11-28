<?php

include __DIR__.'/../../../class/Cargas.php';
header('Content-Type: application/json; charset=utf-8');
//$data = json_decode(file_get_contents('php://input'), true);

$cargas = new Cargas();
$resultados = $cargas->getConfigs();

$response = [
  'success' => true,
  'items' => $resultados,
];
echo json_encode($response);
?>