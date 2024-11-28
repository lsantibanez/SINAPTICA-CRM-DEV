<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $Fecha = $_POST["Fecha"];

    $Semanas = $CalidadClass->getSemanasMesEvaluacionesAutomaticas($Fecha);
    echo json_encode($Semanas);
?>