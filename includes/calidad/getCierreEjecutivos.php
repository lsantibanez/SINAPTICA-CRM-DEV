<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Month = $_POST['Month'];
    $CalidadClass->Id_Cedente = $_POST['IdCedente'];
    echo json_encode($CalidadClass->getCierreEjecutivos($Month));
?>