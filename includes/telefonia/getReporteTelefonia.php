<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/telefonia/telefonia.php");
    QueryPHP_IncludeClasses("db");

	$start 	= $_POST["start"];
	$end 	= $_POST["end"];
	$proveedor = $_POST["proveedor"];

	$telefonia = new Telefonia();
    echo $json_string = json_encode($telefonia->getReporteTelefonia($start, $end , $proveedor));
?>