<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $TipoAcuerdo = $_POST["TipoAcuerdo"];
    $idMandante = $_POST["idMandante"];
    $fecha = $_POST["fecha"];

    $Acuerdos = $DerivacionesClass->getAcuerdosCastigo($TipoAcuerdo,$idMandante,$fecha);
    echo json_encode($Acuerdos);
?>