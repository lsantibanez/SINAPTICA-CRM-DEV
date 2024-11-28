<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $ToReturn = array();
    $Template = $CargaClass->getTemplate($_POST['id']);
    if($Template){
        $ToReturn["HaveTemplate"] = true;
        $ToReturn["id"] = $Template["id"];
        $ToReturn["NombreTemplate"] = $Template["NombreTemplate"];
        $ToReturn["TipoArchivo"] = $Template["Tipo_Archivo"];
        $ToReturn["Separador"] = $Template["Separador_Cabecero"];
        $ToReturn["Cabecero"] = $Template["haveHeader"];
    }
    echo json_encode($ToReturn);
?>