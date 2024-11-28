<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "INSERT INTO subtipo_ticket (IdTipoTicket, Nombre) VALUES ('".$_POST['nombreTipo']."', '".$_POST['nombreSubTipo']."');";
	$run = new DB;
	$data = $run->query($query);
	echo $data
 ?>