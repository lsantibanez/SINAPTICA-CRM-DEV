<?php
	include_once("../functions/Functions.php");
	include_once("../../class/admin/conf_foco.php");
    QueryPHP_IncludeClasses("db");

	$confFoco = new confFoco();
	echo json_encode($confFoco->getFocoConfiguracion());
?>