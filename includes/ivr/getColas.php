<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/ivr/ivr.php");
    include_once("../../class/grupos/grupos.php");
    QueryPHP_IncludeClasses("db");

    $ivr = new Ivr();

    echo json_encode($ivr->getColas());
?>