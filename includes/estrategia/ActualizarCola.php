<?php 
include("../../class/estrategia/estrategias.php");
// include("../../class/db/DB.php");
$Estrategia = new Estrategia();
$Estrategia->ActualizarCola($_POST['Id'],$_POST['ValorCola']);
?>    