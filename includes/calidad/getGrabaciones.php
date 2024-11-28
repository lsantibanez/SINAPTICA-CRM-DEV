<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $Ejecutivo = $_POST["Ejecutivo"];
    $Tipificacion = $_POST["Tipificacion"];
    $Telefono = $_POST["Telefono"];
    $Rut = $_POST["Rut"];
    $ToReturn = $CalidadClass->getGrabaciones($startDate,$endDate,$Ejecutivo,$Tipificacion,$Telefono,$Rut);
    echo json_encode($ToReturn);