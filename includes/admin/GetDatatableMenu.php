<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/admin/conf_menu.php");
    QueryPHP_IncludeClasses("db");
    $confMenu = new confMenu();    
    $confMenu->GetDatatableMenu();
?>