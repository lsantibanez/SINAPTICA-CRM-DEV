<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/bot/bot.php");
    QueryPHP_IncludeClasses("db");

	$bot = new Bot();
	$bots = $bot->getBots();

    $ToReturn = "";

    if(is_array($bots)){
        foreach($bots as $row){
            $ToReturn .= "<option value='" . $row["id"] . "'>" . $row["bot"] . "</option>";
        }
    }

    echo $ToReturn;
?>