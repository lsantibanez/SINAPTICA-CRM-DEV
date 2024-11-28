<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "SELECT
	tickets.IdTickets,
	tickets.IdCliente,
	subtipo_ticket.Nombre,
	tickets.Origen,
	tickets.Departamento,
	Usuarios.usuario,
	tipo_ticket.Nombre,
	subtipo_ticket.Nombre,
	tiempo_prioridad.Nombre,
	tickets.Estado
	FROM
	tickets
	INNER JOIN Usuarios ON tickets.AsignarA = Usuarios.id
	INNER JOIN tipo_ticket ON tickets.Tipo = tipo_ticket.IdTipoTicket
	INNER JOIN subtipo_ticket ON tickets.Subtipo = subtipo_ticket.IdSubTipoTicket
	INNER JOIN tiempo_prioridad ON tickets.Prioridad = tiempo_prioridad.IdTiempoPrioridad
	WHERE tickets.AsignarA = ".$_SESSION['id_usuario'];
	$run = new DB;
	$data = $run->select($query);
	echo count($data);
 ?>