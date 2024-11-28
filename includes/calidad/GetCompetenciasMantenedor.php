<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $idPauta = $_POST["idPauta"];

    $Evaluations = $CalidadClass->getCompetencias($idPauta);
    echo utf8_encode(json_encode($Evaluations));
?>