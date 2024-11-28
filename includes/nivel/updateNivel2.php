<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->updateNivel2($_POST['nivel_1'], $_POST['nombre'], $_POST['id']);
	
?>    