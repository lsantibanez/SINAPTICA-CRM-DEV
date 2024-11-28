<?php 	include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

	$tipoModulo = $_POST["tipoModulo"];
	$protocolo = $_POST["prot"];
	$secure = $_POST["secure"];
	$host = $_POST["host"];
	$port = $_POST["port"];
	$email = $_POST["email"];
	$pass = $_POST["pass"];
	$from = $_POST["from"];
	$fromname = $_POST["fromname"];
	$ConfirmReadingTo = $_POST["ConfirmReadingTo"];

	$WhereIdUsuario = $tipoModulo == "2" ? "and id_usuario = '".$_SESSION["id_usuario"]."'" : "";

	$select_config = "SELECT id FROM control_envio where tipoModulo = '".$tipoModulo."' and Id_Cedente = '".$_SESSION['cedente']."' ".$WhereIdUsuario." ORDER BY id ASC LIMIT 1";

	$config = $db->select($select_config);

	if(count($config) > 0){

		$row_config = $config[0];

		$update_config = "UPDATE control_envio SET protocolo = '".$protocolo."', secure = '".$secure."', host = '".$host."', puerto = '".$port."', email = '".$email."', contrasena = '".$pass."', from_email = '".$from."', from_name = '".$fromname."', ConfirmReadingTo = '".$ConfirmReadingTo."' WHERE id = '".$row_config['id']."'";

		$update = $db->query($update_config);

		if($update !== false){
			echo '1';
		} else {
			echo '2';
		}

	} else {

		$IdUsuarioField = $tipoModulo == "2" ? ",id_usuario" : "";
		$IdUsuarioValue = $tipoModulo == "2" ? ",'".$_SESSION["id_usuario"]."'" : "";

		$save_config = "INSERT INTO control_envio (protocolo, secure, host, puerto, email, contrasena, from_email, from_name, tipoModulo, Id_Cedente, ConfirmReadingTo".$IdUsuarioField.") VALUES('".$protocolo."','".$secure."','".$host."','".$port."','".$email."','".$pass."','".$from."','".$fromname."','".$tipoModulo."','".$_SESSION['cedente']."','".$ConfirmReadingTo."'".$IdUsuarioValue.")";
		
		$save = $db->query($save_config);

		if($save !== false){
			echo '1';
		} else {
			echo '2';
		}
	}

?>