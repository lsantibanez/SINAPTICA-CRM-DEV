<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("reclutamiento");
    QueryPHP_IncludeClasses("db");
    $ReclutamientoClass = new Reclutamiento();
    $Titulo = $_POST['Titulo'];
    $Campo = $_POST['Campo'];
    
    $ToReturn = $ReclutamientoClass->agregarOrdenNotasAspirantesExcel($Titulo,$Campo);
    
    echo json_encode($ToReturn);
?>