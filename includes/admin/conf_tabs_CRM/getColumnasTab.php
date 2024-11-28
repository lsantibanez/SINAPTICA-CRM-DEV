<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/admin/conf_tabs_CRM.php");
    Prints_IncludeClasses("db");
    $ConfTab = new ConfTab();
    $IdTab = $_POST['IdTab'];
    $ToReturn = $ConfTab->getColumnasTab($IdTab);
    echo json_encode($ToReturn);
?>