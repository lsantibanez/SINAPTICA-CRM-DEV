<?php include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

    $id = $_POST["vid"];
    if(isset($_POST["tipoEnvio"]) && ($_POST["tipoEnvio"] == "sms")){
        $tablaQuery = "VariablesSMS";
    }else{
        $tablaQuery = "Variables";
    }

    $query_delete = "DELETE FROM " . $tablaQuery . " WHERE Id=" . $id;
    $return = false;

    $delete = $db->query($query_delete);

    if($delete !== false){
        echo '1';
    } else {
        echo '2';
    }
?>