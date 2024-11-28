<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $CalidadClass->User = $_POST['Ejecutivo'];
    $CalidadClass->startDate = $_POST['startDate'];
    $CalidadClass->endDate = $_POST['endDate'];
    $CalidadClass->Cartera = $_POST['Cartera'];
    $CalidadClass->Tipificacion = $_POST['Tipificacion'];
    echo json_encode($CalidadClass->getRecords());
?>