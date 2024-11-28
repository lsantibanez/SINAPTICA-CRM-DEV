<?php
    include_once("../functions/Functions.php");
    include_once("../../class/paleta_respuestas/paleta_respuestasClass.php");
    QueryPHP_IncludeClasses("db");

    $paleta = new Paleta();

    $NombreNivel1 = $paleta->getNombreNivel1($_POST["idcedente"]);

    json_encode($NombreNivel1);
?>