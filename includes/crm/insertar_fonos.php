<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->insertarFonos($_POST['rut'],$_POST['fono_discado_nuevo'],$_POST['Nombre_nuevo'],$_POST['Cargo_nuevo'],$_POST['Observacion_nuevo'],$_POST['cola'],$_POST['i']);
?>    