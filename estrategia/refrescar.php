<?php 
	include("../class/db/DB.php");
    $db = new DB();
	$id= $_POST['id'];
	$sql=$db->select("SELECT id,id_subquery,query FROM SIS_Querys WHERE id_estrategia=$id");
	foreach($sql as $row){
		$constante = "SELECT Rut FROM Persona WHERE Rut IN ( ";
		$constante2 = " )";
		$constanteDeuda = "SELECT Persona.Rut,Deuda.Deuda FROM Persona,Deuda WHERE Persona.Rut IN ( ";
		$constanteDeuda2 = " ) AND Persona.Rut = Deuda.Rut";
		$subQuery = $row["query"];


		$id1=$row["id"];
		$id_subquery=$row["id_subquery"];
		
		$query1 = $constante.$subQuery.$constante2;
		$queryDeuda = $constanteDeuda.$subQuery.$constanteDeuda2;
		
		$query_1=$db->select($query1);
		foreach($query_1 as $row2){
			$a=$row2['Rut'];
		}
		$numero = count($query_1);
		$numero = number_format($numero, 0, "", ".");
		
		$monto1 = $db->select($queryDeuda);     
		foreach($monto1 as $row){
			$monto_1= $monto_1 + $row['Deuda'];
		}
		echo $monto_1 = '$  '.number_format($monto_1, 0, "", ".");

		
		
		
		
		$db->query("UPDATE SIS_Querys SET cantidad='$numero',monto='$monto_1' WHERE id=$id1");
		
	}
//
?>

	
