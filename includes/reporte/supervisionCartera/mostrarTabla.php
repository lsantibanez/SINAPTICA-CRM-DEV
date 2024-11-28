<?php
include("../../../class/reporte/reporteriaClass.php");
include_once("../../../class/db/DB.php");

$Reporteria = new Reporteria();
$Reporteria->mostrarTabla($_POST['varMandante']);

?>