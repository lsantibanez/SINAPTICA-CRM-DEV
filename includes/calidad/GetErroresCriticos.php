<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Errores = $CalidadClass->GetErroresCriticos();
    $ToReturn = "";
    foreach($Errores as $Error){
        $ToReturn .= "<option value='".$Error["id"]."'>".$Error["Descripcion"]."</option>";
    }
    echo $ToReturn;
?>