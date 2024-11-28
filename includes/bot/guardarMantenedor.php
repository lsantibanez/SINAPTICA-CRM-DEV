<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/bot/bot.php");
    QueryPHP_IncludeClasses("db");

	$ini = trim($_POST["inicio"]);
	$fin = trim($_POST["fin"]);

	$bot = new Bot();
	echo json_encode($bot->guardarMantenedor($ini, $fin));
?>