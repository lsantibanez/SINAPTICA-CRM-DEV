<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $Nombre = $_POST["Nombre"];
    $idPalabra = $_POST["idPalabra"];

    $ToReturn = $SpeechClass->addSinonimo($Nombre,$idPalabra);
    echo json_encode($ToReturn);
?>