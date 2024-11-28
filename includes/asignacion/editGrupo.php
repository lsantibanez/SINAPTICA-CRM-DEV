<?php
	include("../../class/db/DB.php");
	$db = new DB();
	$query = "SELECT IdGrupo, Nombre, Descripcion FROM grupos WHERE IdGrupo = ".$_POST['id'];
	$rows = $db->select($query);
	echo json_encode($rows);
 ?>