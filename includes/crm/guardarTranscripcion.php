<?php 
include("../../class/crm/crm.php");
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
$crm = new crm();
$crm->guardarTranscripcion($_POST['transcripcion'],$_POST['palabras'],$_POST['Url']);
?>    