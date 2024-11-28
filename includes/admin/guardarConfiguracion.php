<?php
	include_once("../functions/Functions.php");
	include_once("../../class/admin/conf_foco.php");
    QueryPHP_IncludeClasses("db");

	$codigo 				= isset($_POST["cod"]) ? trim($_POST["cod"]) : "";
	$sistema 				= isset($_POST["sis"]) ? trim($_POST["sis"]) : "";
	$menu 					= isset($_POST["men"]) ? trim($_POST["men"]) : "";
	$ipServidor 			= isset($_POST["ser"]) ? trim($_POST["ser"]) : "";
	$mandantes 				= isset($_POST["man"]) ? trim($_POST["man"]) : 0;
	$cedentes 				= isset($_POST["ced"]) ? trim($_POST["ced"]) : 0;
	$evaluacion 			= isset($_POST["eva"]) ? trim($_POST["eva"]) : 0;
	$correos 				= isset($_POST["cor"]) ? trim($_POST["cor"]) : 0;
	$sonidoNotificaciones 	= isset($_POST["son"]) ? trim($_POST["son"]) : 0;

	$confFoco = new confFoco();
	echo json_encode($confFoco->guardarConfiguracion($codigo, $sistema, $menu, $ipServidor, $mandantes, $cedentes, $evaluacion, $correos, $sonidoNotificaciones));
?>