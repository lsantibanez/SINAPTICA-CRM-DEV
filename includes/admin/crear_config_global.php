<?php

    include_once("../functions/Functions.php");
    include_once("../../class/admin/config_global_class.php");
    QueryPHP_IncludeClasses("db");
    $conf_global = new ConfigGlobal();
    $crear_conf_global = $conf_global->crear_conf_global( $_POST['idioma'], $_POST['longitud_telefono'], $_POST['moneda'], $_POST['simbolo'], $_POST['time-start'], $_POST['time-end']);
    echo json_encode($crear_conf_global);
    
?> 