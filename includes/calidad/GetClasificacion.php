<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idClasificacion = $_POST['idClasificacion'];
    $ToReturn = $CalidadClass->GetClasificacion($idClasificacion);
    echo utf8_encode(json_encode($ToReturn));
?>