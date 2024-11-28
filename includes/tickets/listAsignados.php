<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "SELECT
	tickets.IdTickets as 'id',
	Usuarios.nombre as Cliente,
	tickets.Origen,
	tickets.Departamento,
	Usuarios.usuario as Usuario,
	tipo_ticket.Nombre as Tipo,
	subtipo_ticket.Nombre as SubTipo,
	tiempo_prioridad.Nombre as Prioridad,
	tickets.Estado
	FROM
	tickets
	INNER JOIN Usuarios ON tickets.AsignarA = Usuarios.id
	INNER JOIN tipo_ticket ON tickets.Tipo = tipo_ticket.IdTipoTicket
	INNER JOIN subtipo_ticket ON tickets.Subtipo = subtipo_ticket.IdSubTipoTicket
	INNER JOIN tiempo_prioridad ON tickets.Prioridad = tiempo_prioridad.IdTiempoPrioridad
	WHERE tickets.AsignarA = ".$_SESSION['id_usuario'];
	$run = new Method;
	if ($_SESSION['MM_UserGroup'] != 1) {
		$lista = $run->listViewTicketsSoporte($query);
	}else{
		$lista = $run->listViewTickets($query);
	}
	echo $lista;
 ?>