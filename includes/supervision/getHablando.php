<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();

    $queue       = isset($_POST['queue']) ? trim($_POST['queue']) : "";

    echo json_encode($Supervision->getHablando($queue));
?>    