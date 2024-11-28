<?php
	include("../../class/db/DB.php");
	$db = new Db();
	$query = "SELECT IdEmpresaExterna, Nombre, Telefono, Correo, Direccion FROM empresa_externa WHERE IdEmpresaExterna = ".$_POST['id'];
	$rows = $db->select($query);
	echo json_encode($rows);
 ?>