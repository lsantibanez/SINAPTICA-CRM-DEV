<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/procedimientos/procedimiento.php");
    QueryPHP_IncludeClasses("db");
    $procedimiento = new Procedimiento(); 
    $ToReturn = $procedimiento->RunProcedimiento($_POST['ID']);
    echo json_encode($ToReturn);
?>