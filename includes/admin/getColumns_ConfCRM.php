<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/crm/crm.php");
    QueryPHP_IncludeClasses("db");
    $CRMClass = new crm();
    $Columnas = $CRMClass->getColumns_ConfCRM();
    echo json_encode($Columnas);
?>