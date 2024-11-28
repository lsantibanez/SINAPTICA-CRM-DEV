<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $Nombre = $_POST["Nombre"];
    $idSinonimo = $_POST["idSinonimo"];

    $ToReturn = $SpeechClass->updateSinonimo($idSinonimo,$Nombre);
    echo json_encode($ToReturn);
?>