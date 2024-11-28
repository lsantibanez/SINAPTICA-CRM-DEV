<?php
    include('../../../includes/functions/Functions.php');
    include('../../../class/db/DB.php');
    include('../../../class/global/cedente.php');

    $CedenteClass = new Cedente();

    $Rut = $_POST["Rut"];

    $ToReturn = $CedenteClass->getRutSearcherData($Rut);
    $ToReturn = utf8_ArrayConverter($ToReturn);
    echo utf8_encode(json_encode($ToReturn));
?>