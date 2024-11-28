<?php 
include_once("../../../includes/functions/Functions.php");
include_once("../../../class/estrategia/estrategia.php");
Prints_IncludeClasses("db");
$Estrategia = new Estrategia();
$Color = $Estrategia->getColor($_POST['id']);
echo json_encode($Color);
?>    