<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Competencia = $_POST['Competencia'];
    $Modulo = $_POST['Modulo'];
    $Topico = $_POST['Topico'];
    $Ejecutivo = $_POST['Ejecutivo'];
    $Month = $_POST['Month'];
    $ToReturn = $CalidadClass->addPlan($Competencia,$Modulo,$Topico,$Ejecutivo,$Month);
    echo json_encode($ToReturn);
?>