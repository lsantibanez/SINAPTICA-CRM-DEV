<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idClasificacion = $_POST["idClasificacion"];
    $nombreClasificacion = $_POST["nombreClasificacion"];
    $notaDesde = $_POST["notaDesde"];
    $notaHasta = $_POST['notaHasta'];
    $descripcionClasificacion = $_POST['descripcionClasificacion'];
    $ToReturn = $CalidadClass->UpdateClasificacion($idClasificacion,$nombreClasificacion,$notaDesde,$notaHasta,$descripcionClasificacion);
    echo utf8_encode(json_encode($ToReturn));
?>