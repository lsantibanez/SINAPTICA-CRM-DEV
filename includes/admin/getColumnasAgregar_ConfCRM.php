<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/crm/crm.php");
    QueryPHP_IncludeClasses("db");
    $CRMClass = new crm();
    $Columnas = $CRMClass->getColumnasAgregar_ConfCRM();
    $options = "";
    foreach($Columnas as $Columna){
        $options .= "<option value='".$Columna["Columna"]."'>".$Columna["Columna"]."</option>";
    }
    echo $options;
?>