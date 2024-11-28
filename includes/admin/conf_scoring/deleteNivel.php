<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();

    $idNivel = $_POST["idNivel"];

    $ToReturn = $ConfScoring->deleteNivel($idNivel);
    echo json_encode($ToReturn);
?>