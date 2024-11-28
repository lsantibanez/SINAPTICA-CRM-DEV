<?php 

	include("../../class/nivel/nivel.php");

	$Nivel = new Nivel();
	$Nivel->storeNivel2($_POST['nivel_1'],$_POST['nombre']);
	
?>    