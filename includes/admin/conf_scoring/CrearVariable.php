<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    
    $TipoVariable = $_POST["TipoVariable"];
    $TipoEscala = $_POST["TipoEscala"];
    $NombreColumna = $_POST["NombreColumna"];
    $Definida = $_POST["Definida"];
    
    $ToReturn = $ConfScoring->CrearVariable($TipoVariable,$TipoEscala,$NombreColumna,$Definida);
    echo json_encode($ToReturn);
?>