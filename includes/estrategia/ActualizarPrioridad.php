<?php 
include("../../class/estrategia/estrategias.php");
include("../../class/db/DB.php");
$Estrategia = new Estrategia();
$Estrategia->ActualizarPrioridad($_POST['Id'],$_POST['ValorPrioridad']);
?>    