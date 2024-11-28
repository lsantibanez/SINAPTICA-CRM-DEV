<?php 
include("../../includes/functions/Functions.php");
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();
    $cola = trim($_POST['cola']);
    $gestionCola = $Supervision->getGestionCola($cola);
    if($gestionCola){
        $gestionCola = utf8_ArrayConverter($gestionCola);
    }
    echo json_encode($gestionCola);
?>    