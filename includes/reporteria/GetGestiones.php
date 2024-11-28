<?php
include_once("../../class/reporte/reporte.php");
$reporte = new Reporte(); 
$data = $_POST['datos'];
$response = $reporte->getAudioGestiones($data);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>