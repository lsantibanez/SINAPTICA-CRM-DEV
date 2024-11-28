<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = 'SELECT
	Usuarios.nombre,
	Usuarios.usuario,
	Usuarios.cargo,
	comentarios_tickets.Comentario,
	comentarios_tickets.Fecha
	FROM
	comentarios_tickets
	INNER JOIN Usuarios ON comentarios_tickets.IdUsuario = Usuarios.id
	WHERE
	comentarios_tickets.IdTicket = '.$_POST['id'].'
	ORDER BY comentarios_tickets.Fecha DESC';
	$run = new DB;
	$data = $run->select($query);
	if (count($data) > 0) {
		$comentarios ="";
		for ($i=0; $i < count($data); $i++) {
			$comentarios.= '<div class="row">
				<div class="pad-all">
					<div class="media mar-btm">
						<div class="media-left">
							<img src="../img/av1.png" class="img-circle" alt="Avatar" width="35">
						</div>
						<div class="media-body">
							<p class="text-lg text-main text-semibold mar-no">'.$data[$i]['nombre'].' - '.$data[$i]['usuario'].'</p>
							<p>'.$data[$i]['cargo'].' - '.date_format(date_create($data[$i]['Fecha']), 'd/m/Y g:i a').'</p>
						</div>
					</div>
					<blockquote class="bq-sm">'.nl2br($data[$i]['Comentario']).'</blockquote>
				</div>
			</div>';
		}
		echo $comentarios;
	}else{
		echo 'No hay comentarios';
	}
 ?>