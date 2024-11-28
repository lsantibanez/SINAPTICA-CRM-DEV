<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "UPDATE tiempo_prioridad SET Nombre='".$_POST['nombre']."', TiempoHora='".$_POST['tiempo']."' WHERE IdTiempoPrioridad = ".$_POST['idUpdatePrioridad'];
	$run = new DB;
	$data = $run->query($query);
	echo $data;
 ?>