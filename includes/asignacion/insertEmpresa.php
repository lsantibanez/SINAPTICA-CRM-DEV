<?php
	include("../../class/db/DB.php");
	$db = new Db();
	
	$query = "INSERT INTO 
				empresa_externa (Nombre, Telefono, Correo, Direccion, IdCedente) 
			VALUES 
				('" . $_POST['nombre'] . "', '" . $_POST['telefono'] . "', 
					'" . $_POST['correo'] . "', '" . $_POST['direccion'] . "', '" . $_SESSION['cedente'] . "')";

	$result = $db->query($query);
	echo $result;
 ?>