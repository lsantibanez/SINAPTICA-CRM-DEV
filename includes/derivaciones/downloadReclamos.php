<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    /* $idMandante = $_POST["idMandante"]; */
    /* $idMandante = "4";
    $fecha = "20180823"; */

    $idMandante = $_GET["idMandante"];
    $fechaStart = $_GET["fechaStart"];
    $fechaEnd = $_GET["fechaEnd"];

    $Reclamos = $DerivacionesClass->downloadReclamos($idMandante,$fechaStart,$fechaEnd);
?>