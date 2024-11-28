<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/admin/conf_tabs_CRM.php");
    Prints_IncludeClasses("db");
    $ConfTab = new ConfTab();
    $Value = $_POST['Value'];
    $ID = $_POST['ID'];
    $Tab = $_POST['Tab'];
    $Sistema = $_POST['Sistema'];
    $IdSistema = $_POST['IdSistema'];
    $ToReturn = $ConfTab->updatePrioridad($Value,$ID,$Tab,$Sistema,$IdSistema);
    echo json_encode($ToReturn);
?>