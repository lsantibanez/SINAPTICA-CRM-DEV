<?php include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

    $id = $_POST["templateid"];
    $canal = $_POST["canal"];

    if($canal == "sms"){
        $query_delete = "DELETE FROM SMS_Template WHERE id = " . $id;
    }else{
        $query_delete = "DELETE FROM EMAIL_Template WHERE Id=".$id;
    }

    $return = false;

    $delete = $db->query($query_delete);

    if($delete !== false){
        echo '1';
    } else {
        echo '2';
    }
?>