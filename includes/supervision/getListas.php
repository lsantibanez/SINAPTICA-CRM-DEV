<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
include_once("../../discador/AGI/phpagi-asmanager.php");
include_once("../../includes/functions/Functions.php");
include_once("../../class/discador/discador.php");

    $Supervision = new Supervision();
    echo json_encode($Supervision->getListasReporte(trim($_POST['lista'])));
?>