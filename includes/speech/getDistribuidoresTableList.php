<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $Mes = $_POST["Date"];

    $Distribuidores = $SpeechClass->getDistribuidoresTableList($Mes);
    $Array = array();
    foreach($Distribuidores as $Distribuidor){
        $ArrayTmp = array();
        $ArrayTmp["Distribuidor"] = utf8_encode($Distribuidor["nombre"]);
        $ArrayTmp["CantTranscripciones"] = $SpeechClass->CantidadTranscripciones_Distribuidor($Distribuidor["id"],$Mes);
        $ArrayTmp["Semanal"] = $Distribuidor["id"];
        $ArrayTmp["Transcripciones"] = $Distribuidor["id"];
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>