<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $TiposContacto = $CalidadClass->getTipoContactoEvaluacionesAutomaticasNoConfigurados();
    $ToReturn = "";
    foreach($TiposContacto as $Tipo){
        $nombreContacto = $Tipo["TipoContacto"];
        $idTipoContacto = $Tipo["idTipoContacto"];
        $ToReturn .= "<option value='".$idTipoContacto."'>".$nombreContacto."</option>";
    }
    echo $ToReturn;
?>