<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $Periodo = $_POST["Periodo"];
    $Mes = date("m",strtotime($Periodo));
    $Ano = date("Y",strtotime($Periodo));

    $ToReturn = $CalidadClass->getPeriodoEvaluacionesSemanales($Mes,$Ano);

    echo json_encode($ToReturn);
?>