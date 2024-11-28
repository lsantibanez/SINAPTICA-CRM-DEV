<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $idSinonimo = $_POST["idSinonimo"];

    $Sinonimo = $SpeechClass->getSinonimo($idSinonimo);
    echo json_encode($Sinonimo);
?>