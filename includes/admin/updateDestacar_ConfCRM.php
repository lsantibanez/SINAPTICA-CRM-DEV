<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/crm/crm.php");
    QueryPHP_IncludeClasses("db");
    $CRMClass = new crm();
    $Value = $_POST['Value'];
    $ID = $_POST['ID'];
    $ToReturn = $CRMClass->updateDestacar_ConfCRM($Value,$ID);
    echo json_encode($ToReturn);
?>