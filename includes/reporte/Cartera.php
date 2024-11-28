<?php
include("../../class/db/DB.php");
include("../../class/reporte/reporteriaClass.php");

$Reporteria = new Reporteria();
$Reporteria->Cartera($_POST['Mandante']);
?>