<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->deleteNivel2($_POST['id']);
	
?>    