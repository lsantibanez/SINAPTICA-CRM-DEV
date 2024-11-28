<?php
	session_start();
	$query = 'SELECT
	id,
	usuario,
	nombre,
	email,
	sexo,
	cargo
	FROM
	Usuarios
	WHERE
		id ='.$_SESSION['id_usuario'];

	include("../../class/db/DB.php");
	$operation = new Db();
	$data = $operation->select($query);

	if (file_exists('../img-profile/'.$_SESSION['id_usuario'].'.jpg')) {
		array_push($data, '<img src="img-profile/'.$_SESSION['id_usuario'].'.jpg?='.rand().'" class="img-lg img-circle" alt="Profile Picture">');
	} else {
		array_push($data, '<img src="../img/av1.png" class="img-lg img-circle" alt="Profile Picture">');
	}

	echo json_encode($data);


?>