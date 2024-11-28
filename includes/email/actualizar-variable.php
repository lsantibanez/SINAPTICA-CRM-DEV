<?php 
	include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

	$id 	= $_POST["id"];
	$tipo 	= $_POST["tipo"];
	$tabla 	= $_POST["tabla"];
	$campos = $_POST["campos"];
	$nombre = $_POST["nombre"];

	$tipoEnvio = $_POST["tipoEnvio"];
	$operacion = $tipo == 'operacion' ? $_POST["operacion"] : '';

	if($tipoEnvio == 0){
		$tablaQuery = "Variables";
	}else{
		$tablaQuery = "VariablesSMS";
	}

	$consultar = "SELECT id FROM " . $tablaQuery . " WHERE variable = '".$nombre."' AND id != '".$id."' and id_cedente='".$_SESSION['cedente']."'";

	$existe = $db->select($consultar);

	if(count($existe) > 0){
		echo '3';
	} else {
		$query_guardar = "UPDATE " . $tablaQuery . " SET variable = '".$nombre."', tabla ='".$tabla."', campo = '".$campos."', operacion = '".$operacion."' WHERE id = '".$id."'";

		$guardar = $db->query($query_guardar);

		if($guardar == false){
			echo '2';
		} else {
			echo '1';
		}
	}
?>