<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();
    
    $data = json_decode($_POST['data']);
    
    echo json_encode($Supervision->guardarContactabilidad($data));
?>    