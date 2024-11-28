<?php 
include_once("../../../includes/functions/Functions.php");
include_once("../../../class/estrategia/estrategia.php");
Prints_IncludeClasses("db");
$Estrategia = new Estrategia();
$Colores = $Estrategia->getColorTableList();
echo json_encode($Colores);
?>    