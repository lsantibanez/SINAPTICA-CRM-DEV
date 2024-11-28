<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    
    $IdVariable = $_POST['IdVariable'];
    $Definida = $_POST['Definida'];
    
    if($IdVariable){
        if($IdVariable == 1){
            $Tabla = 'Deuda_Historico';
        }else{
            $Tabla = 'Persona';
        }
    }else{
        $Tabla = '';
    }

    $Columnas = $ConfScoring->getColumnas($Tabla,$Definida);
    echo json_encode($Columnas);
?>