<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "INSERT INTO tipo_ticket (Nombre) VALUES ('".$_POST['nombreTipo']."');";
	$run = new DB;
	$data = $run->query($query);
	echo $data
 ?>