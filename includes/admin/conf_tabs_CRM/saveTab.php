<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/admin/conf_tabs_CRM.php");
    Prints_IncludeClasses("db");
    $ConfTab = new ConfTab();
    $Tab = $_POST['Tab'];
    $Prioridad = $_POST['Prioridad'];
    $ToReturn = $ConfTab->saveTab($Tab,$Prioridad);
    echo json_encode($ToReturn);
?>