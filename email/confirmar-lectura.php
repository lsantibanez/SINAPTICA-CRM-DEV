<?php 
	include_once("../includes/functions/Functions.php");
    Main_IncludeClasses("db");
    $db = new Db();
	if(isset($_GET['codigo'])){
		$codigo = $_GET['codigo'];
	}else{
		$codigo = '';
	}
    if(isset($_GET['email'])){
		$email = $_GET['email'];
	}else{
		$email = '';
	}
	if($codigo && $email){
		$query = "	UPDATE 
						gestion_correo g
					JOIN 
						envio_email e ON g.id_envio = e.id
					SET
						g.estado = '3'
					WHERE 
						g.correos = '".$email."'
					AND 
						e.codigo = '".$codigo."'";
		$update = $db->query($query);
	}
?>