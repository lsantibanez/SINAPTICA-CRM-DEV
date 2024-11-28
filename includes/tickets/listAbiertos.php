<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "SELECT
	tickets.IdTickets AS `id`,
	Usuarios.nombre AS Cliente,
	tickets.Origen,
	tickets.Departamento,
	Usuarios.usuario AS Usuario,
	tipo_ticket.Nombre AS Tipo,
	subtipo_ticket.Nombre AS SubTipo,
	tiempo_prioridad.Nombre AS Prioridad,
	tickets.Estado
	FROM
	tickets
	INNER JOIN Usuarios ON tickets.AsignarA = Usuarios.id
	INNER JOIN tipo_ticket ON tickets.Tipo = tipo_ticket.IdTipoTicket
	INNER JOIN subtipo_ticket ON tickets.Subtipo = subtipo_ticket.IdSubTipoTicket
	INNER JOIN tiempo_prioridad ON tickets.Prioridad = tiempo_prioridad.IdTiempoPrioridad
	WHERE tickets.Estado = 'Abierto'";
	$run = new Method;
	if ($_SESSION['MM_UserGroup'] != 1) {
		$lista = $run->listViewTicketsSoporte($query);
	}else{
		$lista = $run->listViewTickets($query);
	}
	echo $lista;
 ?>