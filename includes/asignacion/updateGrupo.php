<?php
	session_start();
	require_once('../../class/db/DB.php');
	$db = new DB();
	$query = "UPDATE grupos SET Nombre = '".$_POST['nombre']."', Descripcion = '".$_POST['descripcion']."' WHERE IdGrupo = '".$_POST['id']."'";
	$result = $db->query($query);
 	echo $result;
 ?>