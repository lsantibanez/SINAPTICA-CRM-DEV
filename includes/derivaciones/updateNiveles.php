<?php 
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $tipoNivel = $_POST["tipoNivel"];
    $Niveles = $_POST["Niveles"];
    $ToReturn = $DerivacionesClass->updateNiveles($tipoNivel,$Niveles);
    echo $ToReturn;
?>