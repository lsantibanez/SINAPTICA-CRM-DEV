<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();

    $CalidadClass->Id_Evaluacion = $_POST['Id_Evaluacion'];
    $CalidadClass->Ponderacion = $_POST['Ponderacion'];
    $CalidadClass->Description = utf8_decode($_POST['Description']);
    
    $Return = $CalidadClass->updateEvaluation_Managment();
    echo $Return;
?>