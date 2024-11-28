<?php 
include("../../class/admin/conf_campos_gestion.php");
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->nivel4($_POST['nivel_3'],$_POST['cortar_valor'],$_POST['rut']);
?>    