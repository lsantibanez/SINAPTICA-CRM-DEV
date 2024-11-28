<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("reclutamiento");
    QueryPHP_IncludeClasses("db");
    $ReclutamientoClass = new Reclutamiento();
    $ID = $_POST['ID'];
    $ToReturn = $ReclutamientoClass->deleteOrdenNotasAspirantesExcel($ID);
    echo json_encode($ToReturn);
?>