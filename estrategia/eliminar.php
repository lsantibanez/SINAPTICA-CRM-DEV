<?php
    include("../class/db/DB.php");
    $db = new DB();

    $id=$_POST['id'];
    $db->query("DELETE FROM SIS_Querys WHERE   id=$id")

?>