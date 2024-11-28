<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $CalidadClass->Id_Grabacion = $_POST['Id_Grabacion'];
    echo json_encode($CalidadClass->getAllEvaluations());
?>