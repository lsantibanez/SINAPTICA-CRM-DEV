<?php 
include("../../class/db/DB.php");
include("../../class/crm/crm.php");
$crm = new crm();
$crm->mostrandoFonos($_POST['rut']);
?>    