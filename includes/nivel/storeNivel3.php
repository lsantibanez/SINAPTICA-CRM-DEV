<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->storeNivel3($_POST['nivel_2'],$_POST['nombre'],$_POST['Id_TipoGestion'],$_POST['Ponderacion'],$_POST['Peso']);
	
?>    