<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();

    $cola       = trim($_POST['cola']);
    $rut        = trim($_POST['rut']);
    $nivel1     = trim($_POST['nivel1']);
    $nivel2     = trim($_POST['nivel2']);
    $nivel3     = trim($_POST['nivel3']);
    $fecha_hora = date("Y-m-d H:i:s");;

    $crm->insertarNivelCola($cola, $rut, $nivel1, $nivel2, $nivel3, $fecha_hora);
?>    