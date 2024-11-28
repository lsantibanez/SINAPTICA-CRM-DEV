<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    /* $idMandante = $_POST["idMandante"]; */
    /* $idMandante = "4";
    $tipoRepros = "diario"; // diaria => Diaria ; mensual => Mensual
    $fecha = "20180823"; */

    $idMandante = $_GET["idMandante"];
    $tipoRepros = $_GET["tipoRepro"]; // diaria => Diaria ; mensual => Mensual
    $fecha = $_GET["fecha"];

    $Reprogramaciones = $DerivacionesClass->downloadRepros($idMandante,$tipoRepros,$fecha);
?>