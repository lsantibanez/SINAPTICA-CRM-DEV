<?php 
include_once("../../discador/AGI/phpagi-asmanager.php");
include_once("../../includes/functions/Functions.php");
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();

    $queue       = isset($_POST['queue']) ? trim($_POST['queue']) : "";
    $cedente       = isset($_POST['cedente']) ? trim($_POST['cedente']) : "";

    echo $Supervision->getDescartados($queue,$cedente);
?>