<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    
    $Escala = $_POST["Escala"];
    $Porcentaje = $_POST["Porcentaje"];
    $Valor = $_POST["Valor"];
    $idVariable = $_POST["idVariable"];
    
    $ToReturn = $ConfScoring->ComprobarNivelEscalas($Escala,$Porcentaje,$Valor,$idVariable);
    echo json_encode($ToReturn);
?>