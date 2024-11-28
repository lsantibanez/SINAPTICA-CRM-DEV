<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $Ejecutivo = $_POST['Ejecutivo'];
    $Month = $_POST['Month'];
    $ToReturn = $CalidadClass->getPlans($Ejecutivo,$Month);
    echo json_encode($ToReturn);
?>