<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../plugins/PHPExcel-1.8/Classes/PHPExcel.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    $CalidadClass = new Calidad();
    $TipoBusqueda = $_POST["TipoBusqueda"];
    $ToReturn = $CalidadClass->DownloadInformeGeneral($TipoBusqueda);
    echo json_encode($ToReturn);
?>