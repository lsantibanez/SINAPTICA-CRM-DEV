<?php 
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $tipoTemplate = $_POST["tipoTemplate"];
    $Template = $_POST["Template"];

    $ToReturn = $DerivacionesClass->updateTemplate($tipoTemplate,$Template);
    echo $ToReturn;
?>