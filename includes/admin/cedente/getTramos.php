<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/global/cedente.php");
    include_once("../../../class/db/DB.php");
    $CedenteClass = new Cedente();
    $Columnas = $CedenteClass->getTramos();
    echo json_encode($Columnas);
?>