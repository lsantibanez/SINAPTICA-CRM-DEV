<?php 
include("../../class/db/DB.php");
include("../../class/crm/crm.php");
$crm = new crm();
$crm->deudaRut($_POST['rut']);
?>    