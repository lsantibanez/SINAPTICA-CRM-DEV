<?php 
    include("../../class/db/DB.php");
    include("../../class/exclusiones/exclusiones.php");
    $Exclusion = new Exclusion();
    echo json_encode($Exclusion->getExclusiones());
?>    