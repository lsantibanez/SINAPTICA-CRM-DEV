<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "SELECT
		tipo_ticket.IdTipoTicket as id,
		tipo_ticket.Nombre
	FROM
		tipo_ticket";
	$run = new Method;
	$lista = $run->listView($query);
	echo $lista;
 ?>