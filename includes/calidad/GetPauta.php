<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idPauta = $_POST["idPauta"];
    $ToReturn = $CalidadClass->GetPauta($idPauta);
    echo utf8_encode(json_encode($ToReturn));
?>