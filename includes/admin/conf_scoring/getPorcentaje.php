<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_scoring.php");
    $ConfScoring = new ConfScoring();

    $idPorcentaje = $_POST['idPorcentaje'];

    $Porcentaje = $ConfScoring->getPorcentaje($idPorcentaje);
    echo json_encode($Porcentaje);
?>