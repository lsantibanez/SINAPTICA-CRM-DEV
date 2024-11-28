<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->deleteNivel1($_POST['id']);
	
?>    