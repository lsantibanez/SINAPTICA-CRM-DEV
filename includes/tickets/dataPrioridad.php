<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "INSERT INTO tiempo_prioridad (Nombre, TiempoHora) VALUES ('".$_POST['nombre']."', '".$_POST['tiempo']."');";
	$run = new DB;
	$data = $run->query($query);
	echo $data
 ?>