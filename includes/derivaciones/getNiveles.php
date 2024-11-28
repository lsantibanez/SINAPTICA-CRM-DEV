<?php 
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $tipoNivel = $_POST["tipoNivel"];

    $Niveles = $DerivacionesClass->getNiveles($tipoNivel);
    echo json_encode($Niveles);
?>