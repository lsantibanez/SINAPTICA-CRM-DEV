<?php
	require_once('../../class/db/DB.php');

	$db = new DB();
	$SqlColumns = "select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = '".$_POST['tabla']."'";
	$columns = $db->select($SqlColumns);
	$option = '<option value="">Seleccione...</option>';
	foreach($columns as $column){
		$option.= ' <option>'.$column["Field"].'</option>';
	}

	echo $option;
 ?>