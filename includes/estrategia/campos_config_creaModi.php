<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/estrategia/config_campos.php");
    QueryPHP_IncludeClasses("db");
    $ConfigCampos = new ConfigCampos();
    echo json_encode($ConfigCampos->insertarCamposConfig($_POST['arrayCampos'], $_POST['idTabla']));    
?>