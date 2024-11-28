<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/bot/bot.php");
    QueryPHP_IncludeClasses("db");

	$bot = new Bot();
	$voces = $bot->getVoz();

    $ToReturn = "";

    if(is_array($voces)){
        foreach($voces as $row){
            $ToReturn .= "<option value='" . $row["id"] . "'>" . $row["voz"] . "</option>";
        }
    }

    echo $ToReturn;
?>