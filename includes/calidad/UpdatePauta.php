<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idContacto = $_POST["idContacto"];
    $idPauta = $_POST["idPauta"];
    $nombrePauta = $_POST['nombrePauta'];
    $ToReturn = $CalidadClass->UpdatePauta($idPauta,$idContacto,$nombrePauta);
    echo utf8_encode(json_encode($ToReturn));
?>