<?php 
include("../../class/db/DB.php");
include("../../class/estrategia/estrategia.php");
$Estrategia = new Estrategia();
$Estrategia->SesionEstrategia($_POST['Id']);
?>    