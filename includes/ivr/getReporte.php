<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/ivr/ivr.php");
    QueryPHP_IncludeClasses("db");

    $ivr = new Ivr();

    echo json_encode($ivr->getReporte());
?>