<?php 
include("../../class/sac/sac.php");
include("../../class/db/DB.php");
$sac = new sac();
$tipo = $_POST['tipo'];
$subTipo = $_POST['subTipo'];
$dato = $_POST['dato'];

$sac->getGestion($tipo,$subTipo,$dato);

?>    