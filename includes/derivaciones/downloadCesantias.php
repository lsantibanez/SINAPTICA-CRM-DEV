<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    /* $idMandante = $_POST["idMandante"]; */
    /* $idMandante = "4";
    $tipoCesantia = "diario"; // diaria => Diaria ; mensual => Mensual
    $fecha = "20180823"; */

    $idMandante = $_GET["idMandante"];
    $tipoCesantia = $_GET["tipoCesantia"]; // diario => Diaria ; mensual => Mensual
    $fechaStart = $_GET["fechaStart"];
    $fechaEnd = $_GET["fechaEnd"];

    $Cesantias = $DerivacionesClass->downloadCesantias($idMandante,$tipoCesantia,$fechaStart,$fechaEnd);
?>