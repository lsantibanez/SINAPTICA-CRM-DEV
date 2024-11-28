<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    include_once("../../class/discador/discador.php");
    include_once("../../discador/AGI/phpagi-asmanager.php");
    $crm = new crm();
    $crm->eliminarAnexo($_POST['idCola']);
?>    
