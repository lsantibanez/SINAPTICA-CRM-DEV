<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $ToReturn = $CalidadClass->getClasificaciones_Notas();
    echo utf8_encode(json_encode($ToReturn));
?>