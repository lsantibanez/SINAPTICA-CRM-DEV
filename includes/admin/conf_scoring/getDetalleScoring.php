<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();

    $Rut = $_POST['Rut'];

    $Scoring = $ConfScoring->getDetalleScoring($Rut);
    echo json_encode($Scoring);
?>