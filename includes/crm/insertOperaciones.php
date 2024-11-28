<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$data = $_POST['nombreIn'];
$dataEx = explode("|",$data);
$nombre = $dataEx[0];
$sucursal = $dataEx[1];

$crm->insertOperaciones($_POST['origen'],$nombre,$_POST['clienteIn'],$_POST['observacionIn'],$_POST['tipificacionIn'],$_POST['fono'],$_POST['ori'],$sucursal,$_POST['rut']);
?>    