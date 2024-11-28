<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/global/cedente.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $idMandante = $_GET["idMandante"];
    $fechaStart = $_GET["fechaStart"];
    $fechaEnd = $_GET["fechaEnd"];
    $tipoCompromiso = $_GET["tipoCompromiso"];

    $Reclamos = $DerivacionesClass->downloadCompromisosHitesTributario($tipoCompromiso,$idMandante,$fechaStart,$fechaEnd);
?>