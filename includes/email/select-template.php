<?php 
    include_once("../functions/Functions.php");
    include_once("../../class/email/email.php");
    QueryPHP_IncludeClasses("db");

    $template_id    = $_POST["id"];
    $canal          = isset($_POST["canal"]) ? $_POST["canal"] : "";

    $email = new email();

    echo json_encode($email->selectTemplate($template_id, $canal));
?>