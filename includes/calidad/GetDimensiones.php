<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idCompetencia = $_POST["idCompetencia"];
    $Evaluations = $CalidadClass->getDimensiones($idCompetencia);
    echo utf8_encode(json_encode($Evaluations));
?>