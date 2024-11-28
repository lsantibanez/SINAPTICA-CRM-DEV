<?php
	include("../../class/db/DB.php");
	$db = new Db();
	$query = 'DELETE FROM empresa_externa WHERE IdEmpresaExterna = '.$_POST['id'];
	$result = $db->query($query);
 	echo $result;
 ?>