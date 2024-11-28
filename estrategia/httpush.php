<?php
include("../class/db/DB.php");
$db = new DB();
set_time_limit(0); //Establece el número de segundos que se permite la ejecución de un script.
$fecha_ac = isset($_POST['timestamp']) ? $_POST['timestamp']:0;

$fecha_bd = $_POST['timestamp'];

while( $fecha_bd <= $fecha_ac ){	
	$query3    = "SELECT timestamp FROM mensajes ORDER BY timestamp DESC LIMIT 1";
	$con       = $db->select($query3);
	if(count($con) > 0){
		$ro        = $con[0];
		
		usleep(100000);//anteriormente 10000
		clearstatcache();
		$fecha_bd  = strtotime($ro['timestamp']);
	}else{
		break;
	}
}

$query       = "SELECT * FROM mensajes ORDER BY timestamp DESC LIMIT 1";
$datos_query = $db->select($query);
$ar = array();
foreach($datos_query as $row){
	$ar["timestamp"]   	= strtotime($row['timestamp']);	
	$ar["mensaje"] 	 	= $row['mensaje'];	
	$ar["id"] 		   	= $row['id'];	
	$ar["status"]      	= $row['status'];	
	$ar["tipo"]        	= $row['tipo'];	
}
$dato_json   = json_encode($ar);
echo $dato_json;
?>