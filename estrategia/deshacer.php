<?php 
    include("../class/db/DB.php");
    $db = new DB();
    $id= $_POST['id'];
    $sql=$db->select("SELECT id,id_subquery FROM SIS_Querys WHERE id_estrategia=$id ORDER BY id DESC LIMIT 2");
    foreach($sql as $row){
        $id1=$row["id"];
        $id_subquery=$row["id_subquery"];
        $db->query("DELETE FROM SIS_Querys WHERE id=$id1");
    }
    $db->query("UPDATE SIS_Querys SET carpeta=0,sub=1 WHERE id=$id_subquery");
?>

	
