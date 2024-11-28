<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->storeNivel1($_POST['nombre']);
	
?>    