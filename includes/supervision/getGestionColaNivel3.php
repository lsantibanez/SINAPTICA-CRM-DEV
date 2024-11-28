<?php 
include("../../includes/functions/Functions.php");
include("../../class/supervision/supervision.php");
include("../../class/db/DB.php");
    $Supervision = new Supervision();
    $cola = trim($_POST['cola']);
    $nivel1 = trim($_POST['nivel1']);
    $nivel2 = trim($_POST['nivel2']);

    echo json_encode(utf8_ArrayConverter($Supervision->getGestionColaNivel3($cola, $nivel1, $nivel2)));
?>    