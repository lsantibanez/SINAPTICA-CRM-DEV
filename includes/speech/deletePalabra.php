<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $idPalabra = $_POST["idPalabra"];

    $ToReturn = $SpeechClass->deletePalabra($idPalabra);
    echo json_encode($ToReturn);
?>