<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    echo json_encode($CalidadClass->getEvaluationDetailsCierre($_POST['Id_Evaluaciones']));
?>