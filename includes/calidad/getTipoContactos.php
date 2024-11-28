<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Cedentes = $CalidadClass->getTipoContactos();
    $ToReturn = "<option value='0'>Ninguno</option>";
    foreach($Cedentes as $Cedente){
        $ToReturn .= "<option value='".$Cedente['Id_TipoContacto']."'>".utf8_encode($Cedente['Nombre'])."</option>";
    }
    echo $ToReturn;
?>