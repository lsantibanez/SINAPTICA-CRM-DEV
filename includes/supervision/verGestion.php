<?php 
include("../../class/supervision/supervision.php");
$Supervision = new Supervision();
$Supervision->verGestion($_POST['idcola'],$_POST['label']);
?>    