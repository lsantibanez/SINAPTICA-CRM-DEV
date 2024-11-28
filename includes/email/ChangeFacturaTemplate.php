<?php include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

	$idTemplate = $_POST["idTemplate"];
	$Check = $_POST["Check"];
	
	$query_update = "UPDATE EMAIL_Template set factura = '0', id_usuario=REPLACE(REPLACE(REPLACE(id_usuario,',".$_SESSION["id_usuario"]."',''),'".$_SESSION["id_usuario"].",',''),'".$_SESSION["id_usuario"]."','') where id_cedente='".$_SESSION["cedente"]."' and find_in_set('".$_SESSION["id_usuario"]."',id_usuario)";
	$update = $db->query($query_update);
	
	$SqlTemplate = "select id_usuario from EMAIL_Template where Id='".$idTemplate."'";
	$Template = $db->select($SqlTemplate);

	$Template = $Template[0];
	$Usuarios = $Template["id_usuario"];
	if($Usuarios != ""){
		$UsuariosArray = explode(",",$Usuarios);
		$ArrayTmp = array();
		foreach($UsuariosArray as $Usuario){
			array_push($ArrayTmp,$Usuario);
		}
		$UsuariosNoCheck = implode(",",$ArrayTmp);
		array_push($ArrayTmp,$_SESSION["id_usuario"]);
		$UsuariosCheck = implode(",",$ArrayTmp);
	}else{
		$UsuariosCheck = $_SESSION["id_usuario"];
	}

	switch($Check){
		case "1":
			$Usuarios = $UsuariosCheck;
		break;
		case "0":
			$Usuarios = $UsuariosNoCheck;
		break;
	}
	$Factura = $Usuarios == "" ? "0" : "1";
    echo $query_update = "UPDATE EMAIL_Template set factura = '".$Factura."', id_usuario='".$Usuarios."' where Id='".$idTemplate."'";
	$update = $db->query($query_update);

	if($update == false){
		echo '2';
	} else {
		echo '1';
	}

?>