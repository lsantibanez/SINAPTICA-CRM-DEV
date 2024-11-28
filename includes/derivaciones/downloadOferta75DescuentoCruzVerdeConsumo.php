<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/global/cedente.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $idMandante = $_GET["idMandante"];
    $fechaStart = $_GET["fechaStart"];
    $fechaEnd = $_GET["fechaEnd"];

    $Reclamos = $DerivacionesClass->downloadOferta75DescuentoCruzVerdeConsumo($idMandante,$fechaStart,$fechaEnd);
?>