<?php
include_once("../../class/global/cedente.php");
$Cedente = new Cedente();    
echo json_encode($Cedente->getMandantes());
?>