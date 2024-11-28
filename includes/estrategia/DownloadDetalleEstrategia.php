<?php
  // include("../../includes/functions/Functions.php");
   require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
   ini_set('max_execution_time', 2500);
   include("../../class/estrategia/estrategias.php");
   include("../../class/global/cedente.php");
 //  QueryPHP_IncludeClasses("db");
    $Estrategia = new Estrategia();
    $ToReturn = $Estrategia->DownloadDetalleEstrategia($_GET['IdCola']);
    echo $ToReturn;
?>