<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = 'SELECT
		tiempo_prioridad.IdTiempoPrioridad,
		tiempo_prioridad.Nombre,
		tiempo_prioridad.TiempoHora
	FROM
		tiempo_prioridad
	WHERE
		tiempo_prioridad.IdTiempoPrioridad ='.$_POST['id'];
	$run = new DB;
	$data = $run->select($query);
	if (count($data) > 0) {
		echo json_encode($data);
	}else{
		echo 'false';
	}
 ?>