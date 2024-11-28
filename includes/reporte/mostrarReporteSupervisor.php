<?php
include("../../class/reporte/reporteClass.php");
include("../../class/db/DB.php");

$Reporte = new Reporte();
$Reporte->mostrarReporteSupervisor($_POST['cedente']);

?>