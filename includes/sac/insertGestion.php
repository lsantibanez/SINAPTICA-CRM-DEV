<?php 
include("../../class/sac/sac.php");
include("../../class/db/DB.php");
$sac = new sac();
$tipo = $_POST['tipo'];
$subTipo = $_POST['subTipo'];
$dato = $_POST['dato'];
$tipificacion = $_POST['tipificacion'];
$observacion = $_POST['observacion'];
$fono = $_POST['fono'];

$sac->insertGestion($tipo,$subTipo,$dato,$tipificacion,$observacion,$fono);
?>    