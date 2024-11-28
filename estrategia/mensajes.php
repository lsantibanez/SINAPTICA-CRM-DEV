<?php
	include("../class/db/DB.php");
    $db = new DB();
	$q = "SELECT * FROM mensajes WHERE status = 1";
	$res = $db->select($q);
	$contar = count($res);
	if($contar>0)
	{
		echo "1";
	}
	else
	{
		echo "2";
	}

?>