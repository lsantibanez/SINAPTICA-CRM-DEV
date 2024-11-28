<?php
	class ReporteEjecutivoHora {

		public function insertGrupo($grupo, $idusuarios){
			$db = new DB();
			$idgruponuevo = $db->insert("INSERT INTO grupos( Nombre ) VALUES ('$grupo')");

			foreach($idusuarios as $idusuario){
				$db->query("INSERT INTO grupo_usuario ( id_grupo, id_usuario ) VALUES ('$idgruponuevo', '$idusuario')");
			}
			echo "Registro Insertado";
		}

		public function editarGrupo($idgrupo, $idusuarios ){
			$db = new DB();
		    $db->query("DELETE FROM grupo_usuario WHERE id_grupo = '$idgrupo'");

		    foreach( $idusuarios as $idusuario){
		      $db->query("INSERT INTO grupo_usuario( id_grupo, id_usuario ) VALUES ('$idgrupo', '$idusuario')");
		    }
		    echo "Grupo Actualizado";
		}

		public function getGrupos(){
			$db = new DB();
	        $output = '';
	        $grupos = $db->select("SELECT idGrupo, Nombre FROM grupos");

			foreach($grupos as $grupo){
				$output .= '<option value="'.$grupo["idGrupo"].'">'.$grupo["Nombre"].'</option>';
			}
	        echo $output;
		}

		public function getEjecutivosSelected($idgrupo){
			$db = new DB();
			$output = array();
			$grupos = $db->select("SELECT id_usuario FROM grupo_usuario WHERE id_grupo = '$idgrupo'");
			foreach($grupos as $grupo){
				array_push($output, $grupo['id_usuario']);
		 	}
			return $output;
		}

	    public function getEjecutivos(){
			$db = new DB();
			$output = '';
			$ejecutivos = $db->select("SELECT id, nombre FROM Usuarios ");
	        
	        foreach($ejecutivos as $ejecutivo){
	            $output .= '<option value="'.$ejecutivo["id"].'">'.$ejecutivo["nombre"].'</option>';
	        }
	        echo $output;
		} 
		
		public function getReporte($fechaReporte,$idGrupo){
			$db = new DB();
			echo '<div class="table-responsive">';
			echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
			echo '<thead>';
			echo '<tr><tr>';
			$fechaReporteArray = explode("/",$fechaReporte);
			$fechaReporte = $fechaReporteArray[0]."-".$fechaReporteArray[1]."-".$fechaReporteArray[2];
			
			$queryUsuarios = $db->select("SELECT id_usuario FROM grupo_usuario WHERE id_grupo = $idGrupo");
			$arrayUsuario = array();
			foreach($queryUsuarios as $usuario){
				$rowUsuario = $usuario['id_usuario'];
				array_push($arrayUsuario,$rowUsuario);
			}

			$arrayUsuarioImplode = implode(",",$arrayUsuario);
			$HoraInicio = 8;
			$HoraFinal = 20-1;
			$Hora = "";
			$HoraNext = "";
			$CantidadTotal = 0;
			$arrayUsuarios = array();

			echo "<th class='text-sm'><center>Nombre Ejecutivo</center></th>";
			
			while($HoraInicio<=$HoraFinal){
				
				$Hora = $HoraInicio;
				$HoraInicioNext = $HoraInicio+1;                
				$HoraNext = $HoraInicioNext;
					
				
				echo "<th class='text-xs'><center> ".$Hora." - ".$HoraNext." </center></th>";
				$HoraInicio++;
			}
			
			echo "<th class='text-sm'><center>Total</center></th>";
			
			echo '</thead><tbody>';
			$Usuarios = $db->select("SELECT usuario FROM Usuarios WHERE id IN ($arrayUsuarioImplode)");
	
			$HoraInicio2 = 8;
			$HoraTermino2 = 20-1;
			$CantidadTotalArray = array();
			echo "<tr id=''>";
			foreach($Usuarios as $usuario){
				$Usuario = $usuario["usuario"];
				array_push($arrayUsuarios,"'$Usuario'");
				echo "<tr id=''>";
				echo "<td class='text-sm'>".$Usuario."</td>";
				$i = 8;
				while($i<=20-1){
					$CantidadArray = $db->select("SELECT cantidad FROM RC_TotalGestionesEjecutivosHora WHERE usuario = '$Usuario' AND hora = $i AND fecha = '$fechaReporte' and tipo = 6");
					$Cantidad = count($CantidadArray);
					$can = $Cantidad[0];
					$CantidadTotalArray[$Usuario] = $can;
					echo "<td class='text-sm'><center>".$Cantidad[0]."</center></td>";
					$CantidadTotal =  $Cantidad[0]+$CantidadTotal;
					$i++; 
				}
				$i = 8;
				echo "<td class='text-sm'><center>".$CantidadTotal."</center></td>";
				$CantidadTotal = 0;
				echo '</tr>';
				
			}
			echo "<td class='text-sm'><b>TOTAL</b></td>";
			$k = 8;
			$UsuariosImplode = implode(",",$arrayUsuarios);	
			while($k<=20-1){
				$query = "SELECT SUM(cantidad) as suma  FROM RC_TotalGestionesEjecutivosHora WHERE usuario IN ($UsuariosImplode) AND hora = $k AND fecha = '$fechaReporte' and tipo = 6";
				$CantidadTotal = $db->select($query);
				$CantidadTotalFinal = 0;

				if(count($CantidadTotal) > 0){
					$CantidadTotalFinal =  $CantidadTotal[0]['suma'];
				}

				echo "<td class='text-sm'><center>".$CantidadTotalFinal."</center></td>";
				$k++;
			}

			$queryDia = "SELECT SUM(cantidad) as suma  FROM RC_TotalGestionesEjecutivosHora WHERE usuario IN ($UsuariosImplode)  AND fecha = '$fechaReporte' and tipo = 6";
			$CantidadTotalDia = $db->select($queryDia);
			$CantidadTotalFinalDia = 0;

			if(count($CantidadTotalDia) > 0){
				$CantidadTotalFinal =  $CantidadTotalDia[0]['suma'];
			}

			echo "<td class='text-sm'><center><b>".$CantidadTotalFinalDia."</b></center></td>";

			echo '</tbody></table></div>';
		}
	}
?>