<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idOpcionAfirmacion = $_POST['idOpcionAfirmacion'];
    $ToReturn = $CalidadClass->DeleteOpcionAfirmacion($idOpcionAfirmacion);
    echo utf8_encode(json_encode($ToReturn));
?>