<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/bot/bot.php");
    QueryPHP_IncludeClasses("db");

	$bot = new Bot();
	echo json_encode($bot->getEstadistica($_POST['Estrategia']));
?>