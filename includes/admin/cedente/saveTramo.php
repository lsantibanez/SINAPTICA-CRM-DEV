<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/global/cedente.php");
    include_once("../../../class/db/DB.php");
    
    $Tramo = $_POST['Tramo'];
    $Operacion = $_POST['Operacion'];
    $Desde = $_POST['Desde'];
    $Hasta = $_POST['Hasta'];

    $CedenteClass = new Cedente();
    $ToReturn = $CedenteClass->saveTramo($Tramo,$Operacion,$Desde,$Hasta);
    echo json_encode($ToReturn);
?>