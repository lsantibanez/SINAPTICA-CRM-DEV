<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $Mes = $_POST["Date"];

    $Distribuidores = $SpeechClass->GetReporteTranscripcionesPorDistribuidores($Mes);
    echo json_encode($Distribuidores);
?>