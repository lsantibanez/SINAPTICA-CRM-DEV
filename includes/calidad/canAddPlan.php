<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Ejecutivo = $_POST['Ejecutivo'];
    $Competencia = $_POST['Competencia'];
    $Modulo = $_POST['Modulo'];
    $Topico = $_POST['Topico'];
    $Month = $_POST['Month'];
    $ToReturn = $CalidadClass->canAddPlan($Ejecutivo,$Competencia,$Modulo,$Topico,$Month);
    echo json_encode($ToReturn);
?>