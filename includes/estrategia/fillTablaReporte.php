<?php
    include_once("../../includes/functions/Functions.php");
    include("../../class/db/DB.php");
    include_once("../../class/estrategia/estrategia.php");
    $EstrategiaClass = new Estrategia();
    $Cola = $_POST['Cola'];
    $Periodo = $_POST['Periodo'];
    $TablaReporte = $EstrategiaClass->mostrarTabla($Cola,$Periodo);
    echo $TablaReporte;
?>