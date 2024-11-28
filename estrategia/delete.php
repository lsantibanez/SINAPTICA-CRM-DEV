<?php
    include("../class/db/DB.php");
    $db = new DB();
    $id=$_GET['id_estrategia'];
    $db->query("DELETE FROM SIS_Estrategias WHERE   id=$id");
    $db->query("DELETE FROM SIS_Querys WHERE   id_estrategia=$id");
    header('Location: estrategias.php');
?>