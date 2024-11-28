<?php
	include("../../class/db/DB.php");
	$db = new Db();
	$query = 'DELETE FROM grupos WHERE IdGrupo = '.$_POST['id'];
	$result = $db->query($query);
 	echo $result;
 ?>