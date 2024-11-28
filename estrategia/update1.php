<?php
require_once('../class/db/DB.php'); 
$db = new DB();

$id=$_POST['id'];
$db->query("UPDATE SIS_Querys SET terminal=1 WHERE id=$id")

?>