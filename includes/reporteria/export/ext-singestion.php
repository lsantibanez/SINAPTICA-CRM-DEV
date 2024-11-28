<?php
ini_set('max_execution_time', 2500);
include_once __DIR__.'/../../../class/reporte/reporte.php';
$reporte = new Reporte();
$datos = (array) $_POST;
$fecha = $_POST['fecha'];
$cartera = $_POST['nombreCartera'];
$idCartera = (int) $_POST['idCartera'];
$fileName = "RUTS_SIN_GESTIONES_{$cartera}.csv";

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.$fileName.'"');
echo $reporte->extraSinGestiones(['cartera' => $idCartera]);
exit;
?>