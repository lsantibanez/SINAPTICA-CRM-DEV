<?php
    include("../../includes/functions/Functions.php");
    require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
    ini_set('max_execution_time', 2500);
    include("../../class/estrategia/estrategias.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    
    $Estrategia = new Estrategia();
    $ToReturn = $Estrategia->DownloadResumenAsignacion($_GET['IdCola'],$_GET['Porcentaje']);
    echo $ToReturn;
?>