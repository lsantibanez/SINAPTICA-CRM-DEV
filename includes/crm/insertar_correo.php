<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
if(isset($_POST['Queue'])){
    $Queue = $_POST['Queue'];
}else{
    $Queue = '';
}
$crm = new crm();
$crm->insertarCorreo($_POST['rut'],$_POST['correo_nuevo'],$_POST['cargo'],$_POST['uso'],$_POST['nombre'],$Queue);
?>    