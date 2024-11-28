<?php
	session_start();
	require_once('../../class/db/DB.php');
	$db = new DB();
	$query = "INSERT INTO grupos (Nombre,IdCedente,Descripcion) VALUES ('".$_POST["nombre"]."', '".$_SESSION["cedente"]."','".$_POST["descripcion"]."')";
	$result = $db->query($query);
	echo $result;
 ?>