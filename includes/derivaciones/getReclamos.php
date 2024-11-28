<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $idMandante = $_POST["idMandante"];
    $fechaStart = $_POST["fechaStart"];
    $fechaEnd = $_POST["fechaEnd"];

    $Reclamos = $DerivacionesClass->getReclamos($idMandante,$fechaStart,$fechaEnd);
    echo json_encode($Reclamos);
?>