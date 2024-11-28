<?php
    
    include_once("../../functions/Functions.php"); //incluir clases para la conexión a la bd
    include_once("../../../class/reporteria/ReporteEjecutivoHoraClass.php");
    QueryPHP_IncludeClasses("db");
    $reporteEjecutivoHora = new ReporteEjecutivoHora(); 
    $getReporte = $reporteEjecutivoHora->getReporte($_POST['fechaReporte'],$_POST['idGrupo']);
    json_encode($getReporte);

?>