<?php
    include_once("../functions/Functions.php");
    include_once("../../class/paleta_respuestas/paleta_respuestasClass.php");
    QueryPHP_IncludeClasses("db");

    $paleta = new Paleta();

    $getCedentes = $paleta->getCedentes($_POST['idmandante']);

    json_encode($getCedentes);
?>