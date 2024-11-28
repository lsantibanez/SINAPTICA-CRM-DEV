<?php 
include_once("../../discador/AGI/phpagi-asmanager.php");
include_once("../../includes/functions/Functions.php");
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();

    $queue       = isset($_POST['queue']) ? trim($_POST['queue']) : "";

    echo json_encode($Supervision->getRatiosMonitoreo($queue));
?>