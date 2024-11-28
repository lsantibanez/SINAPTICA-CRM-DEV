<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $idEvaluacion = $_POST['idEvaluacion'];
    $ToReturn = $CalidadClass->buscarGrabacionEvaluacion($idEvaluacion);
    echo json_encode($ToReturn);
?>