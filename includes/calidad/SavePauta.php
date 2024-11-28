<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $tipoPauta = $_POST["tipoPauta"];
    $nombrePauta = $_POST['nombrePauta'];
    $ToReturn = $CalidadClass->SavePauta($tipoPauta,$nombrePauta);
    echo utf8_encode(json_encode($ToReturn));
?>