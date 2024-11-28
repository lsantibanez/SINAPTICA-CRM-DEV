<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "DELETE FROM tickets WHERE IdTickets = ".$_POST['id'];
	$run = new DB;
	$data = $run->query($query);
	echo $data;
 ?>