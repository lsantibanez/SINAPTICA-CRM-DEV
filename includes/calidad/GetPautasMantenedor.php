<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Evaluations = $CalidadClass->getPautas();
    echo utf8_encode(json_encode($Evaluations));
?>