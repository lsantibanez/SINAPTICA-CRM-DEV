<?php 
include("../../includes/functions/Functions.php");
include("../../class/supervision/supervision.php");
include_once("../../plugins/PHPExcel-1.8/Classes/PHPExcel.php");
include("../../class/db/DB.php");

    $Supervision = new Supervision();
    
    $cola = trim($_POST['cola']);
    $nivel1 = trim($_POST['nivel1']);
    $nivel2 = trim($_POST['nivel2']);

    echo json_encode($Supervision->downloadReporteGestion($cola, $nivel1, $nivel2));
?>