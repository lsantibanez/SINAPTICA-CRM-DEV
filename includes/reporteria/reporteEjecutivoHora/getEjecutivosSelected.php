<?php

    include_once("../../functions/Functions.php");
    include_once("../../../class/reporteria/ReporteEjecutivoHoraClass.php");
    QueryPHP_IncludeClasses("db");
    $reporteEjecutivoHora = new ReporteEjecutivoHora();
    $getEjecutivosSelected = $reporteEjecutivoHora->getEjecutivosSelected($_POST['idgrupo']);
    echo json_encode($getEjecutivosSelected);
?>