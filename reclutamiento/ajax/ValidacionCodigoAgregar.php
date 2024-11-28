<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    
    $Codigo = $_POST["Codigo"];

    $ReclutamientoClass = new Reclutamiento();
    $ToReturn = $ReclutamientoClass->ValidacionCodigoAgregar($Codigo);
    echo json_encode($ToReturn);
?>