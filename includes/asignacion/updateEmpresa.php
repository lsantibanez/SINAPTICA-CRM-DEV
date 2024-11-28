<?php
	include("../../class/db/DB.php");
	$db = new Db();
	$query = "UPDATE empresa_externa SET Nombre = '".$_POST['nombre']."', Telefono = '".$_POST['telefono']."', Correo = '".$_POST['correo']."', Direccion = '".$_POST['direccion']."' WHERE IdEmpresaExterna = '".$_POST['id']."'";
	$result = $db->query($query);
 	echo $result;
 ?>