<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    
    $Nivel = $_POST["Nivel"];
    $Codigo = $_POST["Codigo"];

    $ReporteClass = new Reporte();
    $ToReturn = array();
    $ToReturn = $ReporteClass->getTipoGestionData($Nivel,$Codigo);
    echo json_encode($ToReturn);
?>