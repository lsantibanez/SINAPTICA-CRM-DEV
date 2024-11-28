<?php 
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();
 
    $tiempo = trim($_POST['tiempo']);
    $muestra = trim($_POST['muestra']);
    $ratios = json_decode($_POST['ratios']);

    echo json_encode($Supervision->guardarMuestra($tiempo, $muestra, $ratios));
?>    