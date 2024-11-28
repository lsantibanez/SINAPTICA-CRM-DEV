<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idDimension = $_POST["idDimension"];
    $Evaluations = $CalidadClass->getAfirmaciones($idDimension);
    echo utf8_encode(json_encode($Evaluations));
?>