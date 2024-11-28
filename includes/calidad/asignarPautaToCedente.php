<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idCedente = $_POST["idCedente"];
    $idPauta = $_POST["idPauta"];
    $Pautas = $CalidadClass->asignarPautaToCedente($idCedente,$idPauta);
    echo json_encode($Pautas);
?>