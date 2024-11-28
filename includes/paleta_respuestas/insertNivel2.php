<?php
    include_once("../functions/Functions.php");
    include_once("../../class/paleta_respuestas/paleta_respuestasClass.php");
    QueryPHP_IncludeClasses("db");

    $Paleta = new Paleta(); 

    $insertNivel2 = $Paleta->insertNivel2($_POST['idnivel1'], $_POST['nombreRespuesta2'], $_POST['cedentenombre']);

    echo json_encode($insertNivel2);
?>