<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $CalidadClass->User = $_POST['Ejecutivo'];
    $CalidadClass->Id_Mandante = $_POST['Mandante'];
    $Periodo = $_POST['Periodo'];
    
    $Return = $CalidadClass->PuedeHacerCierreDeProceso($Periodo);
    $Array = array();
    $Array["Return"] = false;
    if($Return){
        $Array["Return"] = true;
    }
    echo json_encode($Array);
?>