<?php
    include_once("../functions/Functions.php");
    include_once("../../class/paleta_respuestas/paleta_respuestasClass.php");
    QueryPHP_IncludeClasses("db");

    $Paleta = new Paleta(); 

    $insertNivel3 = $Paleta->insertNivel3($_POST['idnivel2'], $_POST['gestion'], $_POST['ponderacion'], $_POST['peso'], $_POST['nombreRespuesta3'], $_POST['cedentenombre']);

    echo json_encode($insertNivel3);
?>