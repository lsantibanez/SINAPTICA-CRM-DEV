<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    
    $Porcentaje = $_POST["Porcentaje"];
    $idPorcentaje = $_POST["idPorcentaje"];
    
    $ToReturn = $ConfScoring->ComprobarPorcentajesVariableUpdate($Porcentaje,$idPorcentaje);
    echo json_encode($ToReturn);
?>