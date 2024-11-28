<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();
    
    $data = json_decode($_POST['data']);
    $ratio = trim($_POST['ratio']);
    
    echo json_encode($Supervision->guardarRatioTipoContacto($ratio, $data));
?>    