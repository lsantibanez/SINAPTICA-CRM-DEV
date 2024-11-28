<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/email/sms.php");
    QueryPHP_IncludeClasses("db");
    $sms = new sms(); 
    echo json_encode($sms->mensajesEnviados()); 
?>