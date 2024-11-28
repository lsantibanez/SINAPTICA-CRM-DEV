<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/crm/crm.php");
    QueryPHP_IncludeClasses("db");
    $CRMClass = new crm();
    $ID = $_POST['ID'];
    $ToReturn = $CRMClass->deleteColumn_ConfCRM($ID);
    echo json_encode($ToReturn);
?>