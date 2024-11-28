<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = 'SELECT
		tickets.IdTickets,
		tickets.IdCliente,
		tickets.Origen,
		tickets.Departamento,
		tickets.Tipo,
		tickets.Subtipo,
		tickets.Prioridad,
		tickets.AsignarA,
		tickets.Estado,
		tickets.FechaCreacion,
		tickets.Observaciones
	FROM
		tickets
	WHERE IdTickets ='.$_POST['id'];
	$run = new DB;
	$data = $run->select($query);
	if (count($data) > 0) {
		echo json_encode($data);
	}else{
		echo 'false';
	}
 ?>