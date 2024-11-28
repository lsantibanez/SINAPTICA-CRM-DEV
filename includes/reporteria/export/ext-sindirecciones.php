<?php
ini_set('max_execution_time', 2500);
include_once __DIR__.'/../../../class/reporte/reporte.php';
$reporte = new Reporte();
$fileName = "RUTS_SIN_DIRECCIONES.csv";

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.$fileName.'"');
echo $reporte->extraSinDirecciones();
exit;
?>