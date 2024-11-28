<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("reclutamiento");
    QueryPHP_IncludeClasses("db");
    $ReclutamientoClass = new Reclutamiento();
    $Value = $_POST['Value'];
    $ID = $_POST['ID'];
    $ToReturn = $ReclutamientoClass->updatePrioridadOrdenNotasAspirantesExcel($Value,$ID);
    echo json_encode($ToReturn);
?>