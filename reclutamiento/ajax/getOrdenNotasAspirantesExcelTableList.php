<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $Campos = $ReclutamientoClass->getOrdenNotasAspirantesExcelTableList();
    $Array = array();
    foreach($Campos as $Campo){
        $ArrayTmp = array();
        $ArrayTmp["Titulo"] = utf8_encode($Campo["Titulo"]);
        $ArrayTmp["Campo"] = utf8_encode($Campo["Campo"]);
        $ArrayTmp["Prioridad"] = utf8_encode($Campo["Prioridad"]);
        $ArrayTmp["Accion"] = $Campo["id"];
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>