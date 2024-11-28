<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $idPalabra = $_POST["idPalabra"];

    $Sinonimos = $SpeechClass->getSinonimosTableList($idPalabra);
    $Array = array();
    foreach($Sinonimos as $Sinonimo){
        $ArrayTmp = array();
        $ArrayTmp["Sinonimo"] = utf8_encode($Sinonimo["Nombre"]);
        $ArrayTmp["Accion"] = $Sinonimo["id"];
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>