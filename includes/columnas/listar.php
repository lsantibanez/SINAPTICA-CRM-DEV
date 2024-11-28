<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/admin/Columnas.php");
QueryPHP_IncludeClasses("db");
$Columnas = new Columnas();
echo $Columnas->getLista();
?>