<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/bot/bot.php");
    QueryPHP_IncludeClasses("db");

    $bot = new Bot();

    echo json_encode($bot->EliminarCola($_POST['Cola']));
?>