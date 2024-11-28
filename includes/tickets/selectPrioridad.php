<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = 'SELECT IdTiempoPrioridad, Nombre FROM tiempo_prioridad';
	$run = new DB;
	$data = $run->select($query);
	if (count($data) > 0) {
		$list ='<option value="">Seleccione...</option>';
		for ($i=0; $i < count($data); $i++) {
			$list.= '<option value="'.$data[$i]['IdTiempoPrioridad'].'">'.$data[$i]['Nombre'].'</option>';
		}
		echo $list;
	}else{
		echo '<option value="">Seleccione...</option>';
	}
 ?>