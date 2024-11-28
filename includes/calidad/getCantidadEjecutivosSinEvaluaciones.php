<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $FechaInicio = $_POST["FechaInicio"];
    $FechaFin = $_POST["FechaFin"];

    $ToReturn = array();
    $Cantidad = $CalidadClass->getCantidadEjecutivosSinEvaluaciones($FechaInicio,$FechaFin);
    $ToReturn["result"] = $Cantidad;
    echo json_encode($ToReturn);
?>