<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Nombre = $_POST['Nombre'];
    $Ponderacion = $_POST['Ponderacion'];
    $idCompetencia = $_POST['idCompetencia'];
    $ToReturn = $CalidadClass->SaveDimension($Nombre,$Ponderacion,$idCompetencia);
    echo utf8_encode(json_encode($ToReturn));
?>