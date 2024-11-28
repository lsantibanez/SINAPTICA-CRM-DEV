<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "DELETE FROM tiempo_prioridad WHERE IdTiempoPrioridad = ".$_POST['id'];
	$run = new DB;
	$data = $run->query($query);
	echo $data;
 ?>