<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();

    $Variables = $ConfScoring->getVariablesPorcentajeCreate();
    echo json_encode($Variables);
?>