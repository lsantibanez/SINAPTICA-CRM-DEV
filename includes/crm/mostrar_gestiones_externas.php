<?php 
include("../../includes/functions/Functions.php");
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();

echo json_encode(utf8_ArrayConverter($crm->mostrarGestionesExternas($_POST['rut'])));
?>    