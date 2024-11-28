<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idCedente = $_POST["idCedente"];
    $Pautas = $CalidadClass->getPautasCedentes($idCedente);
    echo json_encode($Pautas);
?>