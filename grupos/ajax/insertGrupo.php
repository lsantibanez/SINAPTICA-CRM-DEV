<?php
	//ob_start(); 
	session_start();
	require_once('../../class/db/DB.php');
	$db = new DB();
	$FieldCola = "";
	$ValCola = "";
	if(isset($_POST['cola'])){
		$FieldCola = ",cola";
		$ValCola = ",'".$_POST['cola']."'";
	}
	//echo "INSERT INTO grupos (Nombre,IdCedente".$FieldCola.") VALUES ('".$_POST["nombre"]."', '".$_SESSION["cedente"]."'".$ValCola.")";
	$id = $db->insert("INSERT INTO grupos (Nombre,IdCedente".$FieldCola.") VALUES ('".$_POST["nombre"]."', '".$_SESSION["cedente"]."'".$ValCola.")");
 	
	/* if(isset($_POST['cola'])){
		$personas = $_POST['personas'];
 		$empresas = $_POST['empresas'];
	}else{
		$personas = explode(",", $_POST['personas']);
 		$empresas = explode(",", $_POST['empresas']);
	}
	//print_r($personas);
	if ($resultado) {
 		for ($i=0; $i < count($personas); $i++) {
			$resultado = $db->query('INSERT INTO grupos_personas (IdGrupo, Rut) VALUES ("'.$id.'", "'.$personas[$i].'")');
 		}
 		for ($i=0; $i < count($empresas); $i++) {
			$resultado = $db->query('INSERT INTO grupos_empresas (IdGrupo, IdEmpresaExterna) VALUES ("'.$id.'", "'.$empresas[$i].'")');
 		}
 	} */
	//$content = ob_get_clean();
	if(isset($_POST['cola'])){
		$ToReturn = array();
		$ToReturn["idGrupo"] = $id;
		echo json_encode($ToReturn);
	}
 ?>