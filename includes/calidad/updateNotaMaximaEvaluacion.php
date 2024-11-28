<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $NotaMaximaEvaluacion = $_POST['NotaMaximaEvaluacion'];
    $ToReturn = $CalidadClass->updateNotaMaximaEvaluacion($NotaMaximaEvaluacion);
    echo utf8_encode(json_encode($ToReturn));
?>