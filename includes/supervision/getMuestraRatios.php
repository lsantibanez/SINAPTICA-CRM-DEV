<?php
    include("../../class/supervision/supervision.php");
    include("../../class/db/DB.php");
    
    $Supervision = new Supervision();

    echo json_encode($Supervision->getMuestraRatios());
?>