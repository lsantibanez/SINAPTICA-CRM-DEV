<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
if(isset($_POST['cedente'])){
	$crm->nivel_rapido($_POST['cedente']);
}
?>