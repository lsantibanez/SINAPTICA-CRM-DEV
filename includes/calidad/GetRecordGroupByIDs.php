<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $CalidadClass->User = $_POST['Ejecutivo'];
    $ArrayIDs = $_POST['IDs'];
    echo json_encode($CalidadClass->getRecordGroupByIDs($ArrayIDs));
?>