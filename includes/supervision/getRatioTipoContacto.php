<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();
 
    $ratio = trim($_POST["ratio"]);

    echo json_encode($Supervision->getRatioTipoContacto($ratio));
?>