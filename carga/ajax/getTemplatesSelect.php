<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $Templates = $CargaClass->getTemplates();
    $ToReturn = '';
    foreach($Templates as $Template){
        $ToReturn .= "<option value='".$Template["id"]."'>".$Template["NombreTemplate"]."</option>";
    }
    echo $ToReturn;
?>