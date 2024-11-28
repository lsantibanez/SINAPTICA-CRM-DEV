<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	$rows = $operation -> select("SELECT IdEmpresaExterna, Nombre, Telefono, Correo FROM empresa_externa WHERE IdCedente =".$_SESSION['cedente']);

	$lista = "	<div class='table-responsive'>
					<div class='col-md-12'>
						<button class='btn btn-success' id='CrearEmpresa'>Crear Empresa Externa</button>
						<br>
						<br>
						<table class='table table-striped' id='TableEmpresas'>
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Telefono</th>
									<th>Correo</th>
									<th></th>
								</tr>
							</thead>
							<tbody>";

			if($rows){
				foreach($rows as $row){
					$lista .= '<tr>
							<td>'.$row['Nombre'].'</td>
							<td>'.$row['Telefono'].'</td>
							<td>'.$row['Correo'].'</td>
							<td class="text-center">
								<a id="'.$row['IdEmpresaExterna'].'" class="fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditEmpresa"></a>
								<button type="button" class="btn fa fa-trash btn-danger btn-icon icon-lg DeleteEmpresa" id="'.$row['IdEmpresaExterna'].'"></button>
							</td>
						</tr>';
				}
			}

			$lista.="</tbody></table></div></div>";

	echo $lista;

 ?>