<?php
include("../../../class/reporte/reporteriaClass.php");
include_once("../../../class/db/DB.php");

$Reporteria = new Reporteria();
$Reporteria->puestosTrabajo($_POST['varIdAgente'],$_POST['varMandante']);

?>