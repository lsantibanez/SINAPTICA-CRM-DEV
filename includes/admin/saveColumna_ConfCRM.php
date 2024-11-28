<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/crm/crm.php");
    QueryPHP_IncludeClasses("db");
    $Column = $_POST['Column'];
    $CRMClass = new crm();
    $ToReturn = $CRMClass->saveColumna_ConfCRM($Column);
    echo json_encode($ToReturn);
?>