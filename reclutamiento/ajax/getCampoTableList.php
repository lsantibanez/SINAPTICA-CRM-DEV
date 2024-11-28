<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $Campos = $ReclutamientoClass->getCampoTableList();
    $Array = array();
    foreach($Campos as $Campo){
        $ArrayTmp = array();
        $ArrayTmp["Codigo"] = utf8_encode($Campo["Codigo"]);
        $ArrayTmp["Titulo"] = $Campo["Titulo"];
        $ArrayTmp["ValorEjemplo"] = utf8_encode($Campo["ValorEjemplo"]);
        $ArrayTmp["ValorPredeterminado"] = utf8_encode($Campo["ValorPredeterminado"]);
        $ArrayTmp["Tipo"] = utf8_encode($Campo["Tipo"]);
        $ArrayTmp["Dinamico"] = $Campo["Dinamico"];
        $ArrayTmp["Mandatorio"] = $Campo["Mandatorio"];
        $ArrayTmp["Deshabilitado"] = $Campo["Deshabilitado"];
        $ArrayTmp["Contenedor"] = utf8_encode($Campo["Contenedor"]);
        $ArrayTmp["Accion"] = $Campo["id"];
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>