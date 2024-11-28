<?php
    ini_set("memory_limit","-1");
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();

    $Desde = $_GET["Desde"];
    $Hasta = $_GET["Hasta"];
    $idCedente = $_GET["idCedente"];

    $ToReturn = $ReporteClass->CrearReporteDeGestionEstructurado($Desde,$Hasta,$idCedente);
    //echo json_encode($ToReturn);
    echo $ToReturn;
?>