
<?php
    include_once("../functions/Functions.php");
    include_once("../../class/paleta_respuestas/paleta_respuestasClass.php");
    QueryPHP_IncludeClasses("db");

    $paleta = new Paleta();

    $NombreNivel2 = $paleta->getNombreNivel2($_POST["idnivel1"]);

    json_encode($NombreNivel2);
?>