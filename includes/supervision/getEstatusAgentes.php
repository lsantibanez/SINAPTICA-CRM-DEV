<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();

    $queue       = isset($_POST['queue']) ? trim($_POST['queue']) : "";
    $cedente       = isset($_POST['cedente']) ? trim($_POST['cedente']) : "";

    echo json_encode($Supervision->getEstatusAgentes($queue,$cedente));
?>    