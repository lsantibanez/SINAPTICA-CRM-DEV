<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $CalidadClass->Id_Evaluacion = $_POST['Id_Evaluacion'];
    
    $Return = $CalidadClass->deleteEvaluation_Managment();
    echo $Return;
?>