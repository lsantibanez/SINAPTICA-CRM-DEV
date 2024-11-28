<?php

	if (!class_exists('Db')) {
		if(!@include("../class/db/DB.php")){
		}
	}

	class Omni{

		public function navBar(){
			if(isset($_SESSION['cedente'])){
				$db = new DB();
				$query = "SELECT 
					m.nombre as Nombre_Mandante, 
					c.Nombre_Cedente
				FROM 
					mandante as m
				INNER JOIN 
					mandante_cedente mc
				ON 
					mc.Id_Mandante = m.id
				INNER JOIN 
					Cedente c
				ON 
					mc.Id_Cedente = c.Id_Cedente
				WHERE 
					c.Id_Cedente = '".$_SESSION['cedente']."'
				LIMIT 1";
				$mandante_cedente = $db->select($query);
				if($mandante_cedente){
					$mandante_cedente = $mandante_cedente[0];
					echo '<li class="dropdown">';
						echo '<li id="idSeleccionarCedente"><a href="#demo-tabs-box-3" data-toggle="tab" title="Cambiar de Empresa o Proyecto"><span class="text-mint">'.$mandante_cedente["Nombre_Mandante"]. ' - ' .$mandante_cedente["Nombre_Cedente"].'</span><button class="btn btn-icon icon-lg fa fa-retweet" value=""></button></a></li>';
					echo '</li>';
				}
			}
	  	}

	  	public function getPermisos($enlace){

	  		$db = new Db();
	        $query = "SELECT menu_roles.* FROM menu_roles 
	        			LEFT JOIN menu ON menu_roles.id_menu = menu.id_menu  
	        			WHERE menu.enlace = '".$enlace."'";
	        $permisos = $db->select($query);
	        $str = '';

	        if($permisos){
	        	foreach((array) $permisos as $permiso){
	        		if($str){
	        			$str = $str . ',' . $permiso['id_rol'];
	        		}else{
	        			$str = $permiso['id_rol'];
	        		}
	        	}
	        }

	        return $str;
		}
		
		public function getUnusedColumns($Table){
			$db = new Db();
			$ToReturn = '';
			$query = "SELECT GROUP_CONCAT(column_name ORDER BY ordinal_position) AS columns
						FROM
							information_schema.COLUMNS 
						WHERE
							table_schema = 'foco' 
							AND table_name = '".$Table."'";
			$ColumnsArray = $db->select($query);
			if($ColumnsArray){
				$Columns = explode(',',$ColumnsArray[0]['columns']);
				foreach ($Columns as $Column) {
					$Result = $db->select("SELECT COUNT(*) as Cantidad FROM ".$Table." WHERE ".$Column." IS NOT NULL");
					if(!$Result[0]['Cantidad']){
						$ToReturn .= ','.$Column;
					}
				}
			}
			return $ToReturn;
		}
	}
?>
