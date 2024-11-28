<?php
require_once('../class/db/DB.php'); 
$db = new DB();

$id=$_POST['id'];
$db->query("UPDATE SIS_Querys SET terminal=0 WHERE id=$id")

?>