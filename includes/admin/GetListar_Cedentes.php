<?php
include_once("../../class/global/cedente.php");
//QueryPHP_IncludeClasses("db");
$Cedente = new Cedente();
echo json_encode($Cedente->getCedentes());
?>