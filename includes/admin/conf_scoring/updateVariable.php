<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    
    $TipoVariable = $_POST["TipoVariable"];
    $TipoEscala = $_POST["TipoEscala"];
    $NombreColumna = $_POST["NombreColumna"];
    $Definida = $_POST["Definida"];
    $idVariable = $_POST['idVariable'];
    
    $ToReturn = $ConfScoring->updateVariable($TipoVariable,$TipoEscala,$NombreColumna,$Definida,$idVariable);
    echo json_encode($ToReturn);
?>