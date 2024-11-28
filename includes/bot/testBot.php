<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/bot/bot.php");
    QueryPHP_IncludeClasses("db");

    $bot = new Bot();
    $ToReturn = $bot->testBot($_POST['id'],$_POST['Nombre'],$_POST['Fono']);
	echo json_encode($ToReturn);
?>