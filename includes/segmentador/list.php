<?php
include __DIR__.'/../../class/segmentador/Segmentador.php';
header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true);

$Segmentador = new Segmentador();
$resultados = $Segmentador->listarSegmentos($data);

$response = [
  'success' => true,
  'items' => $resultados,
];
echo json_encode($response);
?>