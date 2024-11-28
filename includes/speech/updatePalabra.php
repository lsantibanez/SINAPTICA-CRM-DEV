<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("speech");
    $SpeechClass = new Speech();

    $NombreMetrica = $_POST["NombreMetrica"];
    $Grupo = $_POST["Grupo"];
    $ValorMetrica = $_POST["ValorMetrica"];
    $PesoGrupo = $_POST["PesoGrupo"];
    $Veces = $_POST["Veces"];
    $idPalabra = $_POST["idPalabra"];

    $ToReturn = $SpeechClass->updatePalabra($idPalabra,$NombreMetrica,$Grupo,$ValorMetrica,$PesoGrupo,$Veces);
    echo json_encode($ToReturn);
?>