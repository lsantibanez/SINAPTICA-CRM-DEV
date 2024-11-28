<?php
    include_once("../functions/Functions.php");
    include_once("../../class/paleta_respuestas/paleta_respuestasClass.php");
    QueryPHP_IncludeClasses("db");

    $Paleta = new Paleta(); 

    $insertNivel1 = $Paleta->insertNivel1($_POST['nombreRespuesta'], $_POST['idcedente'], $_POST['cedentenombre']);

    echo json_encode($insertNivel1);
?>