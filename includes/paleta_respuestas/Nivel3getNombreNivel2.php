
<?php
    include_once("../functions/Functions.php");
    include_once("../../class/paleta_respuestas/paleta_respuestasClass.php");
    QueryPHP_IncludeClasses("db");

    $paleta = new Paleta();

    $NombreNivel2 = $paleta->Nivel3getNombreNivel2();

    json_encode($NombreNivel2);
?>