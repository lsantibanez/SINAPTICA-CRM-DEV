<?php 
    include("../../class/crm/crm.php");
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $crm = new crm();
    $ToReturn = $crm->getInvoiceAmount($_POST['facturas']);
    echo json_encode((int)$ToReturn);
?>