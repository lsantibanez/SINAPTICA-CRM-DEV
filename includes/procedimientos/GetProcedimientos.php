<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/procedimientos/procedimiento.php");
    QueryPHP_IncludeClasses("db");
    $procedimiento = new Procedimiento(); 
    $procedimientos = $procedimiento->getProcedimientos();
    echo json_encode($procedimientos);
?>