<?php
include("../../class/reporte/reporteClass.php");
include("../../class/db/DB.php");

$Reporte = new Reporte();
$Reporte->mostrarReporteOnce($_POST['fecha'],$_POST['cedente']);

?>