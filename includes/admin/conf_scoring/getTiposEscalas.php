<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();

    $TiposEscalas = $ConfScoring->getTiposEscalas();
    echo json_encode($TiposEscalas);
?>