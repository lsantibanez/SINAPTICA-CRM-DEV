<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $Palabras = $SpeechClass->getPalabrasTableList();
    $Array = array();
    foreach($Palabras as $Palabra){
        $ArrayTmp = array();
        $ArrayTmp["NombreMetrica"] = $Palabra["NombreMetrica"];
        $ArrayTmp["Grupo"] = $Palabra["Grupo"];
        $ArrayTmp["ValorMetrica"] = $Palabra["ValorMetrica"];
        $ArrayTmp["PesoGrupo"] = $Palabra["PesoGrupo"];
        $ArrayTmp["Veces"] = $Palabra["Veces"];
        $ArrayTmp["CantSinonimos"] = $SpeechClass->CantidadSinonimos($Palabra["id"]);
        $ArrayTmp["Accion"] = $Palabra["id"];
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>