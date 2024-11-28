<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->updateNivel1($_POST['nombre'], $_POST['id']);
	
?>    