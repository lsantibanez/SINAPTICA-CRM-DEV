<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();

    $idVariable = $_POST['idVariable'];

    $Variable = $ConfScoring->getVariable($idVariable);
    echo json_encode($Variable);
?>