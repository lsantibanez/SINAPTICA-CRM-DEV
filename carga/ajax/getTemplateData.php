<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $ToReturn = array();
    $ToReturn["Template"] = array();
    $ToReturn["Sheets"] = array();
    $ToReturn["Template"]["HaveTemplate"] = false;
    $Template = $CargaClass->getTemplate($_POST['id_template']);
    if($Template){
        $ToReturn["Template"]["HaveTemplate"] = true;
        $ToReturn["Template"]["TipoArchivo"] = $Template["Tipo_Archivo"];
        $Sheets = $CargaClass->getSheets($_POST['id_template']);
        $ToReturn["Sheets"] = $Sheets;
    }
    echo json_encode($ToReturn);
?>