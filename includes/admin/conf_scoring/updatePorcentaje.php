<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    
    $idVariable = $_POST["idVariable"];
    $Porcentaje = $_POST["Porcentaje"];
    $Scoring = $_POST["Scoring"];
    $idPorcentaje = $_POST["idPorcentaje"];
    
    $ToReturn = $ConfScoring->updatePorcentaje($idVariable,$Porcentaje,$Scoring,$idPorcentaje);
    echo json_encode($ToReturn);
?>