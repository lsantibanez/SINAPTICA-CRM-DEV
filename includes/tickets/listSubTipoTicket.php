<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "SELECT
		subtipo_ticket.IdSubTipoTicket as 'id',
		tipo_ticket.Nombre as Tipo,
		subtipo_ticket.Nombre as SubTipo
	FROM
		subtipo_ticket
	INNER JOIN tipo_ticket ON subtipo_ticket.IdTipoTicket = tipo_ticket.IdTipoTicket";
	$run = new Method;
	$lista = $run->listView($query);
	echo $lista;
 ?>