<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $ToReturn = $CalidadClass->getTipoContactoEvaluacionesAutomaticas();
    echo json_encode($ToReturn);
?>