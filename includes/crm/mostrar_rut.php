<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
if(isset($_POST['estrategia'])){
    $estrategia = trim($_POST['estrategia']);
}else{
    $estrategia = '';
}
$crm->mostrarRut($_POST['prefijo'],$estrategia,$_POST['orden'],$_POST['tipo']);
?>    