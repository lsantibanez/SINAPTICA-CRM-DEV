<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->deleteNivelRapido($_POST['id']);
	
?>    