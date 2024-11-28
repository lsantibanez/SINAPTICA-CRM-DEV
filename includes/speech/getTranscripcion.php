<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $idTranscripcion = $_POST["idTranscripcion"];

    $Transcripcion = $SpeechClass->getTranscripcion($idTranscripcion);
    $ArrayURL = explode("/",$Transcripcion["URL"]);
    $Transcripcion["RUTA"] = "../../".$ArrayURL[3]."/".$ArrayURL[5]."/".$ArrayURL[6]."/".$ArrayURL[7]."/".$ArrayURL[8];
    echo json_encode($Transcripcion);
?>