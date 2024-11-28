<?php
    
    include_once("../../functions/Functions.php"); //incluir clases para la conexión a la bd
    include_once("../../../class/reporteria/ReporteEjecutivoHoraClass.php");
    QueryPHP_IncludeClasses("db");
    $reporteEjecutivoHora = new ReporteEjecutivoHora(); 
    $editarGrupo = $reporteEjecutivoHora->editarGrupo($_POST['editGrupo'], $_POST['editEjecutivos']);
    echo json_encode($editarGrupo);

?>