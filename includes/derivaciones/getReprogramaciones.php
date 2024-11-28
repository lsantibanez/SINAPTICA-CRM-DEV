<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $TipoReprogramacion = $_POST["TipoReprogramacion"];
    $idMandante = $_POST["idMandante"];
    $fecha = $_POST["fecha"];

    $Reprogramacion = $DerivacionesClass->getReprogramaciones($TipoReprogramacion,$idMandante,$fecha);
    echo json_encode($Reprogramacion);
?>