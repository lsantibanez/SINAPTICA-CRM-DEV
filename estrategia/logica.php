<?php 
	include("../class/db/DB.php");
    $db = new DB();
	$id=$_POST['id_columna'];
	$columnas=$db->select("SELECT columna,logica FROM SIS_Columnas where id=$id");
	foreach($columnas as $row){
		$columna=$row["columna"];	
		$logica=$row["logica"];
		if($logica==0)
		{ 
			echo '<select name="logica" class="select1" id="logica"><option value="0">Seleccione Lógica</option><option value="<">Menor</option><option value=">">Mayor</option><option value="=">Igual</option><option value="<=">Menor o Igual</option><option value=">=">Mayor o Igual</option><option value="!=">Distinto</option></select>';
		}
		else
		{
			echo "<select name='logica' class='select1' id='logica'><option value='0'>Seleccione Lógica</option><option value='='>Igual</option><option value='!=''>Distinto</option></select>";
		}
	}
?>

