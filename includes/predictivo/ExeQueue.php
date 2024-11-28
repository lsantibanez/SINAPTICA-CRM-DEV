<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/discador/discador.php");
    include_once("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");

    $Cola = $argv[1];
    $Provider = $argv[2];
    $Discador = new Discador($Cola);
    $Array = $Discador->Start($Provider);
?>   