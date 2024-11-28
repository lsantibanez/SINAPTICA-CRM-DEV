<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $idPersonal = $_POST["idPersonal"];
    $cantidadEvaluaciones = $_POST["cantidadEvaluaciones"];

    $ToReturn = $CalidadClass->updateEvaluacionesSemanalesPorEvaluador($idPersonal,$cantidadEvaluaciones);
    echo json_encode($ToReturn);
?>