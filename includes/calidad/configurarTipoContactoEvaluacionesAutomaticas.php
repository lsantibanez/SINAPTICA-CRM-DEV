<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $idTipoContacto = $_POST["idTipoContacto"];
    $duracionMin = $_POST["duracionMin"];
    $duracionMax = $_POST["duracionMax"];

    $ToReturn = $CalidadClass->configurarTipoContactoEvaluacionesAutomaticas($idTipoContacto,$duracionMin,$duracionMax);
    echo json_encode($ToReturn);
?>