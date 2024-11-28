<?php

require_once __DIR__.'/../../class/segmentador/Segmentador.php';
$class = new Segmentador();
$response = [
  'success' => true,
  'items' => $class->listParams()
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>