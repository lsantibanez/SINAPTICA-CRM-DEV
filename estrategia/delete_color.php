<?php
    include("../class/db/DB.php");
    $db = new DB();
    $id=$_GET['id'];
    $db->query("DELETE FROM SIS_Categoria_Fonos  WHERE  id=$id");
    header('Location: categoria_fonos.php');
?>