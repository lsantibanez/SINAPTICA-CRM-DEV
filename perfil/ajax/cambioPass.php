<?php
	include("../../class/db/DB.php");
	session_start();
	$query = 'SELECT clave FROM Usuarios WHERE id = '.$_SESSION['id_usuario'];
	$db = new Db();
	$data = $db->select($query);
	$password_anterior = $data[0]['clave'];
	$password_verify = password_verify($_POST['pass'], $password_anterior);
	if ($password_verify) {
		$clave = password_hash($_POST['newPass'], PASSWORD_BCRYPT);
		$query = "UPDATE Usuarios SET clave = '".$clave."' WHERE id = ".$_SESSION['id_usuario'];
		$result = $db->query($query);
		if($result){
			echo true;
		}else{
			echo false;
		}
	}else{
		echo false;
	}
 ?>