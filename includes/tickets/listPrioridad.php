<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "SELECT
		IdTiempoPrioridad as id,
		tiempo_prioridad.Nombre,
		tiempo_prioridad.TiempoHora as 'Tiempo en horas'
		FROM
		tiempo_prioridad";
	$run = new Method;
	$lista = $run->listView($query);
	echo $lista;
 ?>