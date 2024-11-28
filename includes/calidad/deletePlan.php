<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $ID = $_POST['ID'];
    $ToReturn = $CalidadClass->deletePlan($ID);
    echo json_encode($ToReturn);
?>