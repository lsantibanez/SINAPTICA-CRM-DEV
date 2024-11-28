<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $Mes = $_POST["Date"];
    $idDistribuidor = $_POST["idDistribuidor"];

    $Transcripciones = $SpeechClass->getTranscripcionesTableList($idDistribuidor,$Mes);
    $Array = array();
    foreach($Transcripciones as $Transcripcion){
        $ArrayTmp = array();
        $ArrayTmp["Ver"] = $Transcripcion["Transcripcion"];
        $ArrayTmp["FechaHora"] = date("d-m-Y H:i:s",strtotime($Transcripcion["FechaHora"]));
        $ArrayTmp["PalabrasClaves"] = $Transcripcion["PalabrasClaves"];
        $ArrayTmp["PalabrasEncontradas"] = $Transcripcion["PalabrasEncontradas"];
        $ArrayTmp["PorcentajeCumplimiento"] = number_format($SpeechClass->getPorcentajeCumplimiento($Transcripcion["Transcripcion"]),2);
        $ArrayTmp["Cumplimiento"] = $ArrayTmp["PorcentajeCumplimiento"] >= 60 ? "Cumple" : "No Cumple";
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>