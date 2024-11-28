<?php 
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    $Supervision = new Supervision();

    $queue      = isset($_POST['queue']) ? trim($_POST['queue']) : "";
    $estatus      = isset($_POST['estatus']) ? trim($_POST['estatus']) : "";
    $cedente      = isset($_POST['cedente']) ? trim($_POST['cedente']) : "";

    echo json_encode($Supervision->getAgentesEstatus($queue, $estatus, $cedente));
?>    