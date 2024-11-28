<?php
include __DIR__.'/../../class/segmentador/Segmentador.php';
header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true);

$Segmentador = new Segmentador();
$resultados = $Segmentador->creaSegmento($data);

$response = [
  'success' => true,
  'result' => $resultados,
];
echo json_encode($response);
?>