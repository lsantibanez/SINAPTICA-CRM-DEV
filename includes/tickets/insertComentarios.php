<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "INSERT INTO comentarios_tickets (IdTicket, Comentario, IdUsuario, Fecha) VALUES ('".$_POST['idTicket']."', '".$_POST['comentario']."', '".$_SESSION['id_usuario']."', '".date("Y-m-d H:i:s")."')";
	$run = new DB;
	$data = $run->query($query);
	echo $data
 ?>