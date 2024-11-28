<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $idDistribuidor = $_POST["idDistribuidor"];
    $Mes = $_POST["Date"];

    $Semanas = $SpeechClass->GetReporteSemanalByDistribuidor($idDistribuidor,$Mes);
    echo json_encode($Semanas);
?>