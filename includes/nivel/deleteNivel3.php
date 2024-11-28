<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->deleteNivel3($_POST['id']);
	
?>    