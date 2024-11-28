<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();
    $Id = $_POST['Id'];
    $Escalas = $ConfScoring->getEscalas($Id);
    echo json_encode($Escalas);
?>