<?php
	/**
	* Métodos globales
	*/

	class Method {
		
		function listView($post) {

			$db = new DB();
			$con = $db->connect();
			if ($con) {
				$resultado = mysqli_query($con, $post);
				if ($resultado) {
					while ($field = mysqli_fetch_field($resultado)) {
						$fields[] = $field->name;
						$table[] = $field->table;
					}
					$tabla = "<table class='table table-striped table-hover tabeData'><thead><tr>";
					for ($i=1; $i < count($fields) ; $i++) {
						$tabla.="<th>".$fields[$i]."</th>";
					}
					$tabla.="<th></th></tr></thead><tbody>";
					$resultado = $db->select($post);
					foreach($resultado as $fila){
						$rows[] = $fila;
					}
					if (isset($rows)) {
						for ($i=0; $i < count($rows) ; $i++) {
							$tabla.= '<tr>';
							foreach ($rows[$i] as $clave => $valor) {
								if($clave != 'id')
									$tabla.="<td>".$valor."</td>";
							}
							$tabla.='<td class="optionTable">
								<i class="fa fa-pencil-square-o update-'.$table[0].'" attr="'.$rows[$i]['id'].'"  aria-hidden="true" title="Editar"></i>
								<i class="fa fa-trash-o delete-'.$table[0].'"  attr="'.$rows[$i]['id'].'" aria-hidden="true" title="Eliminar"></i>
								</td>';
							$tabla.= '</tr>';
						}
					}
					$tabla.="</tbody></table>";
					return $tabla;
				}else{
					return 'Problemas en el query de consulta';
				}
			}else{
				return 'No hay conexion';
			}
		}

		function listViewDelete($post) {
			$db = new DB();
			$con = $db->connect();

			if ($con) {

				$resultado = mysqli_query($con, $post);

				if ($resultado) {

					while ($field = mysqli_fetch_field($resultado)) {
						$fields[] = $field->name;
						$table[] = $field->table;
					}
		
					$tabla = "<table class='table table-striped table-hover tabeData'><thead><tr>";
					for ($i=0; $i < count($fields) ; $i++) {
						$tabla.="<th>".$fields[$i]."</th>";
					}
					$tabla.="<th></th></tr></thead><tbody>";

					$resultado = $db->select($post);

					foreach($resultado as $fila){

						$rows[] = $fila;
					}
					if (isset($rows)) {
						for ($i=0; $i < count($rows) ; $i++) {
							$tabla.= '<tr>';
							foreach ($rows[$i] as $clave => $valor) {
								$tabla.="<td>".$valor."</td>";
							}
							$tabla.='<td class="optionTable">
								<i class="fa fa-trash-o delete-'.$table[0].'"  attr="'.$rows[$i]['id'].'" aria-hidden="true" title="Eliminar"></i>
								</td>';
							$tabla.= '</tr>';
						}
					}
					$tabla.="</tbody></table>";
					return $tabla;
				}else{
					return 'Problemas en el query de consulta';
				}
			}else{
				return 'No hay conexion';
			}
		}

		function listViewSingle($post) {
			$db = new DB();
			$con = $db->connect();

			if ($con) {

				$resultado = mysqli_query($con, $post);

				if ($resultado) {

					while ($field = mysqli_fetch_field($resultado)) {
						$fields[] = $field->name;
						$table[] = $field->table;
					}
		
					$tabla = "<table class='table table-striped table-hover tabeData'><thead><tr>";
					for ($i=0; $i < count($fields) ; $i++) {
						$tabla.="<th>".$fields[$i]."</th>";
					}
					$tabla.="<th></th></tr></thead><tbody>";

					$resultado = $db->select($post);

					foreach($resultado as $fila){

						$rows[] = $fila;
					}
					if (isset($rows)) {
						for ($i=0; $i < count($rows) ; $i++) {
							$tabla.= '<tr>';
							foreach ($rows[$i] as $clave => $valor) {
								$tabla.="<td>".$valor."</td>";
							}
							$tabla.= '</tr>';
						}
					}
					$tabla.="</tbody></table>";
					return $tabla;
				}else{
					return 'Problemas en el query de consulta';
				}
			}else{
				return 'No hay conexion';
			}
		}

		function listViewTickets($post) {
			$db = new DB();
			$con = $db->connect();

			if ($con) {

				$resultado = mysqli_query($con, $post);

				if ($resultado) {

					while ($field = mysqli_fetch_field($resultado)) {
						$fields[] = $field->name;
						$table[] = $field->table;
					}
		
					$tabla = "<table class='table table-striped table-hover tabeData'><thead><tr>";
					for ($i=0; $i < count($fields) ; $i++) {
						$tabla.="<th>".$fields[$i]."</th>";
					}
					$tabla.="<th></th></tr></thead><tbody>";

					$resultado = $db->select($post);

					foreach($resultado as $fila){

						$rows[] = $fila;
					}
					if (isset($rows)) {
						for ($i=0; $i < count($rows) ; $i++) {
							$tabla.= '<tr>';
							foreach ($rows[$i] as $clave => $valor) {
								$tabla.="<td>".$valor."</td>";
							}
							$tabla.='<td class="optionTable">
								<i class="fa fa-trash-o delete-'.$table[0].'"  attr="'.$rows[$i]['id'].'" aria-hidden="true" title="Eliminar"></i>
								<i class="fa fa-pencil-square-o update-'.$table[0].'" attr="'.$rows[$i]['id'].'"  aria-hidden="true" title="Editar"></i>
								<i class="fa fa-commenting comentarios" attr="'.$rows[$i]['id'].'"  data-toggle="modal" data-target="#comentarios" aria-hidden="true" title="Editar"></i>
								</td>';
							$tabla.= '</tr>';
						}
					}
					$tabla.="</tbody></table>";
					return $tabla;
				}else{
					return 'Problemas en el query de consulta';
				}
			}else{
				return 'No hay conexion';
			}
		}
		function listViewTicketsSoporte($post) {
			$db = new DB();
			$con = $db->connect();

			if ($con) {

				$resultado = mysqli_query($con, $post);

				if ($resultado) {

					while ($field = mysqli_fetch_field($resultado)) {
						$fields[] = $field->name;
						$table[] = $field->table;
					}
		
					$tabla = "<table class='table table-striped table-hover tabeData'><thead><tr>";
					for ($i=0; $i < count($fields) ; $i++) {
						$tabla.="<th>".$fields[$i]."</th>";
					}
					$tabla.="<th></th></tr></thead><tbody>";

					$resultado = $db->select($post);

					foreach($resultado as $fila){

						$rows[] = $fila;
					}
					if (isset($rows)) {
						for ($i=0; $i < count($rows) ; $i++) {
							$tabla.= '<tr>';
							foreach ($rows[$i] as $clave => $valor) {
								$tabla.="<td>".$valor."</td>";
							}
							$tabla.='<td class="optionTable">
								<i class="fa fa-calendar-times-o finalizar-'.$table[0].'" attr="'.$rows[$i]['id'].'"  aria-hidden="true" title="Finalizar Ticket"></i>
								<i class="fa fa-commenting comentarios" attr="'.$rows[$i]['id'].'"  data-toggle="modal" data-target="#comentarios" aria-hidden="true" title="Hacer un Comentario"></i>
								</td>';
							$tabla.= '</tr>';
						}
					}
					$tabla.="</tbody></table>";
					return $tabla;
				}else{
					return 'Problemas en el query de consulta';
				}
			}else{
				return 'No hay conexion';
			}
		}
		function listViewServicios($post) {
			$db = new DB();
			$con = $db->connect();

			if ($con) {

				$resultado = mysqli_query($con, $post);

				if ($resultado) {
					while ($field = mysqli_fetch_field($resultado)) {
						$fields[] = $field->name;
						$table[] = $field->table;
					}
					$tabla = "<table class='table table-striped table-hover tabeData'><thead><tr>";
					for ($i=1; $i < count($fields) ; $i++) {
						$tabla.="<th>".$fields[$i]."</th>";
					}
					$tabla.="<th></th></tr></thead><tbody>";
					$resultado = $db->select($post);

					foreach($resultado as $fila){
						$rows[] = $fila;
					}
					if (isset($rows)) {
						for ($i=0; $i < count($rows) ; $i++) {
							$tabla.= '<tr>';
							foreach ($rows[$i] as $clave => $valor) {
								if($clave != 'id')
									$tabla.="<td>".$valor."</td>";
							}
							$tabla.='<td class="optionTable">
								<i class="fa fa-pencil-square-o mostrarDatosTecnicos" attr="'.$rows[$i]['id'].'"  data-toggle="modal" data-target="#modalEditar" aria-hidden="true" title="Ver"></i>
								<i class="fa fa-times eliminarServicio" attr="'.$rows[$i]['id'].'" aria-hidden="true" title="eliminar"></i>
								</td>';
							$tabla.= '</tr>';
						}
					}
					$tabla.="</tbody></table>";
					return $tabla;
				}else{
					return 'Problemas en el query de consulta';
				}
			}else{
				return 'No hay conexion';
			}
		}
	}
 ?>