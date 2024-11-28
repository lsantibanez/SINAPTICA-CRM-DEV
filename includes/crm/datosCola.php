<?php 
    include("../../includes/functions/Functions.php");
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    include_once("../../class/discador/discador.php");
    include_once("../../discador/AGI/phpagi-asmanager.php");
    
    $Anexo = "SIP/".$_SESSION["anexo_foco"];

    $crm = new crm();
    $focoConfig = getFocoConfig();


    $asm = new AGI_AsteriskManager();
    $asm->connect($focoConfig["IpServidorDiscado"],"lponce","lponce");
    $Result= $asm->Command("queue show");

    $db = new DB();
    $SqlQueues = "select Queue from Asterisk_All_Queues order by Queue";
    $Queues = $db->select($SqlQueues);
    foreach($Queues as $Queue){
        $Command = "queue remove member ".$Anexo." from ".$Queue["Queue"];
        $Result = $asm->Command($Command);
    }


    $crm->insertarDatosCola($_POST['idCola']);
?>    
