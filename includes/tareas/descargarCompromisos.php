<?php
    include("../../includes/functions/Functions.php");
    require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
    //ini_set('max_execution_time', 2500);
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();

    $Cedente = $_GET['Cedente'];
    $startDate = $_GET["startDate"];
    $endDate = $_GET["endDate"];
    $ToReturn = $tareas->descargarCompromisos($Cedente,$startDate,$endDate);
    //echo json_encode($ToReturn);
    echo $ToReturn;
?>