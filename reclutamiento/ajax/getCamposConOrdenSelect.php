<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $Campos = $ReclutamientoClass->getCamposConOrdenNoSeleccionado();
    $ToReturn = "";
    foreach($Campos as $Campo){
        $ToReturn .= "<option value='".$Campo["id"]."'>".utf8_encode($Campo["Codigo"])."</option>";
    }
    echo $ToReturn;
?>