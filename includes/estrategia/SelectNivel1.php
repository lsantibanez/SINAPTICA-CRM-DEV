<?php
require_once("../functions/Functions.php");
require_once('../../class/db/DB.php'); 
$db = new DB();
$id_codigo = $_POST['id'];
$lista = $_POST['cola'];
$periodo = $_POST['periodo'];
$cedente = $_SESSION['cedente'];
$total = $_POST['total'];
$tipo = $_POST['tipo'];
if($lista==-1)
{
	$q1 = $db->select("SELECT id,Respuesta_N2 FROM Nivel2 WHERE Id_Nivel1 = $id_codigo ");
	foreach($q1 as $r){
		$id = $r[0]; 
		$nombre = utf8_encode($r[1]); 

		$q2 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g,Periodo_Gestion p WHERE g.resultado_n2 = $id AND g.cedente = $cedente  and g.fechahora BETWEEN p.Fecha_Inicio and p.Fecha_Termino and p.cedente = g.cedente");


		$res = count($q2);


		$q4 = $db->select("SELECT Rut FROM Mejor_Gestion_Periodo WHERE Respuesta_N2 = $id AND Id_Cedente = $cedente ");
		$r4 = count($q4); 

		$ratio = $r4==0 ? 0 : $res/$r4; 
		$ratio = number_format($ratio, 2, '.', '');
		$porc = $total==0 ? number_format(0, 2, '.', '') : number_format($r4/$total*100, 2, '.', '');
			echo "<tr class='removerNivel1' id='$id'><td><span class='text-xs'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button class='btn btn-icon icon-lg fa fa-plus-square nivel2 lvl2'   value='' id='e$id'></button> $nombre</span></td><td>$res
				</td><td>$r4</td><td>$ratio</td><td>$porc %</td></tr>";
	}	
}
else
{



	$q1 = $db->select("SELECT id,Respuesta_N2 FROM Nivel2 WHERE Id_Nivel1 = $id_codigo ");
	foreach($q1 as $r){
		$id = $r["id"]; 
		$nombre = utf8_encode($r["Respuesta_N2"]); 

		$q2 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g,Periodo_Gestion p WHERE g.resultado_n2 = $id AND g.cedente = $cedente and g.lista=$lista and g.fechahora BETWEEN p.Fecha_Inicio and p.Fecha_Termino and p.cedente = g.cedente");


		$res = count($q2);


		$q4 = $db->select("SELECT Rut FROM Mejor_Gestion_Periodo WHERE Respuesta_N2 = $id AND Id_Cedente = $cedente and lista=$lista");
		$r4 = count($q4); 

		$ratio = $r4==0 ? 0 : $res/$r4; 
		$ratio = number_format($ratio, 2, '.', '');
		$porc = $total==0 ? number_format(0, 2, '.', '') : number_format($r4/$total*100, 2, '.', '');
			echo "<tr class='removerNivel1' id='$id'><td><span class='text-xs'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button class='btn btn-icon icon-lg fa fa-plus-square nivel2 lvl2'   value='' id='e$id'></button> $nombre</span></td><td>$res
				</td><td>$r4</td><td>$ratio</td><td>$porc %</td></tr>";
	}	
}	

?>