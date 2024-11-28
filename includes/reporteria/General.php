<?php
ini_set('max_execution_time', 2500);
include_once("../../class/reporte/reporte.php");
$reporte = new Reporte();
$datos = (array) $_POST;
$ToReturn = $reporte->generaGeneral($datos);
?>