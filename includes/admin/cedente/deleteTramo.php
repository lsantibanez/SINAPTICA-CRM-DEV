<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/global/cedente.php");
    include_once("../../../class/db/DB.php");

    $CedenteClass = new Cedente();
    $ID = $_POST['ID'];
    $ToReturn = $CedenteClass->deleteTramo($ID);
    echo json_encode($ToReturn);
?>