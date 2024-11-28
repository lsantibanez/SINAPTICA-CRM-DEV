<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $idPalabra = $_POST["idPalabra"];

    $Palabra = $SpeechClass->getPalabra($idPalabra);
    echo json_encode($Palabra);
?>