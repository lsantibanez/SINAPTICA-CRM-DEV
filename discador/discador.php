<?php
    include ("AGI/phpagi-asmanager.php");
    include ("../class/discador/discador.php");
    include ("../class/db/DB.php");
    $Fono = $_POST['Tel'];
    $Anexo = $_POST['Anexo'];
    $Cedente = $_POST['Cedente'];
    $User = $_POST['User'];
    $Provider = $_POST['Provider'];
    $CodigoFoco = $_POST["CodigoFoco"];
    $FonoPrefix = $_POST["FonoPrefix"];
    $DiscadorClass = new Discador();
    $ToReturn = $DiscadorClass->Discar($Fono,$Anexo,$Cedente,$User,$Provider,$CodigoFoco,$FonoPrefix);
    echo json_encode($ToReturn);
?>

