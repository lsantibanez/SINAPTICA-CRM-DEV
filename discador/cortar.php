<?php
    include ("AGI/phpagi-asmanager.php");
    include ("../class/discador/discador.php");
    include ("../class/db/DB.php");
    $Anexo = $_POST['Anexo'];
    $DiscadorClass = new Discador();
    $DiscadorClass->CortarDiscado($Anexo);
?>



