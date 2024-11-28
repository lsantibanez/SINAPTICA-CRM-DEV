<?php
	include("../../class/db/DB.php");
	$db = new Db();
	$query = "SELECT IdGrupo, Nombre, Descripcion FROM grupos WHERE IdCedente = '".$_SESSION['cedente']."'";
	$rows = $db->select($query);
	$lista = "<div class='table-responsive'>
				<div class='col-md-12'>
					<button class='btn btn-success' id='CrearGrupo'>Crear Grupo</button>
					<br>
					<br>
					<table class='table table-striped' id='TableGrupos'>
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Descripción</th>
									<th></th>
								</tr>
							</thead>
							<tbody>";
			if($rows){
				foreach($rows as $row){
					$lista.= '<tr>
							<td>'.$row['Nombre'].'</td>
							<td>'.$row['Descripcion'].'</td>
							<td class="text-center">
								<a id="'.$row['IdGrupo'].'" class="fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditGrupo"></a>
								<button type="button" class="btn fa fa-trash btn-danger btn-icon icon-lg DeleteGrupo" id="'.$row['IdGrupo'].'"></button>
							</td>
						</tr>';
				}
			}

			$lista.="</tbody></table></div></div>";

	echo $lista;

 ?>