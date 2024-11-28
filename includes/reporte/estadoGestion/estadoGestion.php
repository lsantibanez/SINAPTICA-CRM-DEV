<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();
    $ToReturn = array();
    $ToReturn["Saldos"] = $ReporteClass->getSaldoEstadoGestionData();
    $ToReturn["Recuperos"] = $ReporteClass->getRecuperoEstadoGestionData();
    echo json_encode($ToReturn);
?>