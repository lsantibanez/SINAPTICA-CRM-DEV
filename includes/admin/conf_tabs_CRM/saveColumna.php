<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/admin/conf_tabs_CRM.php");
    Prints_IncludeClasses("db");
    $ConfTab = new ConfTab();
    $Tabla = $_POST['Tabla'];
    $Columna = $_POST['Columna'];
    $Prioridad = $_POST['Prioridad'];
    $IdTab = $_POST['IdTab'];
    $ToReturn = $ConfTab->saveColumna($Tabla,$Columna,$Prioridad,$IdTab);
    echo json_encode($ToReturn);
?>