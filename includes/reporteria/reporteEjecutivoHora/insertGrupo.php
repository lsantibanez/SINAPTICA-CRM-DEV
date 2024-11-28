<?php
    
    include_once("../../functions/Functions.php"); //incluir clases para la conexión a la bd
    include_once("../../../class/reporteria/ReporteEjecutivoHoraClass.php");
    QueryPHP_IncludeClasses("db");
    $reporteEjecutivoHora = new ReporteEjecutivoHora(); 
    $insertGrupo = $reporteEjecutivoHora->insertGrupo($_POST['crearnombregrupo'], $_POST['newEjecutivos']);
    echo json_encode($insertGrupo);

?>