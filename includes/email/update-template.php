<?php 
    include_once("../functions/Functions.php");
    include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

    $title      = $_POST["tname"];
    $id         = $_POST["templateid"];
    $template   = $_POST["template"];
    $tasunto    = isset($_POST["tasunto"]) ? $_POST["tasunto"] : "";
    
    $email = new email();

    echo $email->updateTemplate($id, $title, $template, $tasunto);

?>