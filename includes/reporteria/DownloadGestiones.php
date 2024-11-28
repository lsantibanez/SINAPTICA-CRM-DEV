<?php
//include("../../includes/functions/Functions.php");
//require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
ini_set('max_execution_time', 2500);
include_once("../../class/reporte/reporte.php");
//QueryPHP_IncludeClasses("db");
$reporte = new Reporte();
$datos = (array) $_POST['datos'];
$ToReturn = $reporte->downloadGestiones($datos);
//echo $ToReturn; 
?>