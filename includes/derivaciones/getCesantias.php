<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $TipoCesantia = $_POST["TipoCesantia"];
    $idMandante = $_POST["idMandante"];
    $fechaStart = $_POST["fechaStart"];
    $fechaEnd = $_POST["fechaEnd"];

    $Cesantias = $DerivacionesClass->getCesantias($TipoCesantia,$idMandante,$fechaStart,$fechaEnd);
    echo json_encode($Cesantias);
?>