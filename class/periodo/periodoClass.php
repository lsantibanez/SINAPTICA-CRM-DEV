<?php
	class Periodo {

		public function insertPeriodoDias($Id_Cedente, $dia_inicio, $dia_fin ){
			$db = new DB();

			if(!empty($Id_Cedente) && !empty($dia_inicio) && !empty($dia_fin)){
				$dia_inicio_clear = $dia_fin_clear = '';
				$dia_inicio_clear = trim($dia_inicio);
				$dia_fin_clear = trim($dia_fin);

				$db->query("INSERT INTO Cedente_Dias(Id_Cedente, dia_inicio, dia_fin)
							 VALUES ('" . $Id_Cedente . "', '" . $dia_inicio_clear . "', '" . $dia_fin_clear . "')");
				echo "Periodo Creado";
			} else {
				echo "Todos los campos son requeridos";
				return false;
			}
		}

		public function getCedente(){
			$db = new DB();
	        $output = '';

			$cedentes = $db->select("SELECT Id_Cedente, Nombre_Cedente FROM Cedente");

			if(count($cedentes) > 0){
				foreach($cedentes as $cedente){
					$output .= '<option value="'.$cedente["Id_Cedente"].'">'.$cedente["Nombre_Cedente"].'</option>';
				}
			}
	        echo $output;
		}
    }
?>