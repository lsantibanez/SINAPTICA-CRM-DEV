<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idPauta = $_POST["idPauta"];
    $Nombre = $_POST['Nombre'];
    $Descripcion = $_POST['Descripcion'];
    $Ponderacion = $_POST['Ponderacion'];
    $Tag = $_POST['Tag'];
    $ToReturn = $CalidadClass->SaveCompetencia($idPauta,$Nombre,$Descripcion,$Ponderacion,$Tag);
    echo utf8_encode(json_encode($ToReturn));
?>