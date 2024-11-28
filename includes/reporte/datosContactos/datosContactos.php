<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();
    $ToReturn = array();
    $ToReturn["Fonos"] = $ReporteClass->getFonoContactosData();
    $ToReturn["Mails"] = $ReporteClass->getMailContactosData();
    echo json_encode($ToReturn);
?>