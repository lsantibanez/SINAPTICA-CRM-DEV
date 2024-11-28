<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $NotaMaximaEvaluacion = $CalidadClass->getNotaMaximaEvaluacion();
    echo utf8_encode($NotaMaximaEvaluacion);
?>