<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/admin/conf_tabs_CRM.php");
    Prints_IncludeClasses("db");
    $ConfTab = new ConfTab();
    $ID = $_POST['ID'];
    $Tabla = $_POST['Tabla'];
    $ToReturn = $ConfTab->getColumnas($ID,$Tabla);
    echo json_encode($ToReturn);
?>