<?php
    include("../../class/db/DB.php");
    include_once("../functions/Functions.php"); //incluir clases para la conexión a la bd
    include_once("../../class/periodo/periodoClass.php");
    QueryPHP_IncludeClasses("db");
    $periodo = new Periodo(); 
    $crearPeriodoDias = $periodo->insertPeriodoDias( $_POST['cedente'], $_POST['periodo_inicio'], $_POST['periodo_fin']);
    echo json_encode($crearPeriodoDias);

?>