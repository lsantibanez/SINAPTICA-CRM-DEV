<?php
class Categoriafono{

    public function getCategoriaTableList(){
		$db = new DB();
		$ToReturn = array();
		$query = "SELECT id,color,tipo_contacto,tipo_contacto_query,dias,cond1,cant1,logica,cond2,cant2,w,color_hex,color_nombre,sel,tipo_var,proceso,cantidad,prioridad,mundo FROM SIS_Categoria_Fonos WHERE sel = 0 AND mundo = 1"; 
		$categorias = $db->select($query);

		if($categorias){
			foreach($categorias as $row){
				$array = array();
				$array['id'] = $row['id'];
				$array['color'] = $row['color'];
				$array['color_nombre'] = $row['color_nombre'];
				$array['color_hex'] = $row['color_hex'];
				if($row["color"] == 0){ 
					$array['prioridad'] =  "--"; 
				}else { 
					$array['prioridad'] = $row["prioridad"]; 
				}
				if($row["color"] == 0){ 
					$array['tipo_var'] =  "--"; 
				}else { 
					$array['tipo_var'] = $row["tipo_var"]; 
				}
				if($row["color"] == 0){ 
					$array['dias'] =  "--"; 
				}else { 
					$array['dias'] = $row["dias"]; 
				}
				if($row["color"] == 0){ 
					$row["cond1"] = "--";
				}else { 
					if($row["cond1"] == 1){ 
						$array["cond1"] = "Menor";
					}else if($row["cond1"] == 2){ 
						$array["cond1"] = "Menor o Igual"; 
					}else if($row["cond1"] == 3){ 
						$array["cond1"] = "Igual";
					}else if($row["cond1"] == 4){ 
						$array["cond1"] = "Mayor";
					}elseif($row["cond1"] == 5){ 
						$array["cond1"] = "Mayor o Igual";
					} 
				}
				$array['cant1'] = $row['cant1'];

				if($row["color"] == 0){ 
					$array['logica'] = "--";
				}else { 
					if($row["logica"] == 1){ 
						$array['logica'] = "N/A";
					}else if($row["logica"] == 2){  
						$array['logica'] = "Y";
					} else if($row["logica"] == 3){  
						$array['logica'] = "O";
					} 
				}
				if($row["color"] == 0){ 
					$array["cond2"] = "--";
				}else { 
					if($row["cond2"] == 1){ 
						$array["cond2"] = "Menor";
					}else if($row["cond2"] == 2){ 
						$array["cond2"] = "Menor o Igual"; 
					}else if($row["cond2"] == 3){ 
						$array["cond2"] = "Igual";
					}else if($row["cond2"] == 4){ 
						$array["cond2"] = "Mayor";
					}elseif($row["cond2"] == 5){ 
						$array["cond2"] = "Mayor o Igual";
					}else{
						$array["cond2"] = "--";
					}
				}
				if($row["color"] == 0){ 
					$array["cant2"] = "--";
				}else{ 
					if($row["logica"] == 1){ 
						$array["cant2"] = "--";
					}else { 
						$array["cant2"] = $row["cant2"]; 
					} 
				}
				$array["sel"] = $row["sel"];
				array_push($ToReturn, $array);
			}  
		}    
		return $ToReturn;                                          
	}
    public function getListarCategoria(){
        $db = new Db();
        $categoriaArray = array();
        $Sql = "SELECT * FROM SIS_Colores";
        $categorias = $db -> select($Sql);
        foreach($categorias as $categoria){
        $Array = array();
        $Array['categoria'] = utf8_encode($categoria["nombre"]);
        $Array['idCategoria'] = $categoria["id"];
        array_push($categoriaArray,$Array);
        }
        return $categoriaArray;
    }

    public function getListarTipoContacto(){
        $db = new Db();
        $contactoArray = array();
        $Sql = "SELECT * FROM Tipo_Contacto WHERE mundo = 1";
        $contactos = $db -> select($Sql);
        foreach($contactos as $contacto){
        $Array = array();
        $Array['contacto'] = utf8_encode($contacto["Nombre"]);
        $Array['idContacto'] = $contacto["Id_TipoContacto"];
        array_push($contactoArray,$Array);
        }
        return $contactoArray;
    }

    public function crearCategoria($datos){
        $db = new Db();
        $datos['tipoContacto'] = implode(",", $datos['tipoContacto']);
        $tipo_contacto_query = "Id_TipoGestion=".$datos['tipoContacto'];

        $SqlDatos = "SELECT nombre, color FROM SIS_Colores WHERE id = '".$datos['color']."'";
        $DatosColor = $db -> select($SqlDatos);
        $nomColor = $DatosColor[0]['nombre'];
        $hexa = $DatosColor[0]['color'];
        
        echo $query = "INSERT INTO SIS_Categoria_Fonos (color, tipo_contacto, dias, cond1, cant1, cond2, cant2, logica, tipo_contacto_query, w, color_nombre, color_hex, tipo_var, prioridad) VALUES('".$datos['color']."', '".$datos['tipoContacto']."', '".$datos['dias']."', '".$datos['condicion1']."', '".$datos['cantidad1']."', '".$datos['condicion2']."', '".$datos['cantidad2']."', '".$datos['logica']."', '".$tipo_contacto_query."', '0', '".$nomColor."', '".$hexa."', '".$datos['nombreContacto']."', '".$datos['prioridad']."')";
        $db->query($query);
    }

    public function updateCategoria($datos){
        $db = new Db();
        $datos['tipoContacto'] = implode(",", $datos['tipoContacto']);
        $tipo_contacto_query = "Id_TipoGestion=".$datos['tipoContacto'];

        $SqlDatos = "SELECT nombre, color FROM SIS_Colores WHERE id = '".$datos['color']."'";
        $DatosColor = $db -> select($SqlDatos);
        $nomColor = $DatosColor[0]['nombre'];
        $hexa = $DatosColor[0]['color'];
        
        $query = "UPDATE SIS_Categoria_Fonos SET color = '".$datos['color']."', tipo_contacto = '".$datos['tipoContacto']."', dias = '".$datos['dias']."', cond1 = '".$datos['condicion1']."', cant1 = '".$datos['cantidad1']."', cond2 = '".$datos['condicion2']."', cant2 = '".$datos['cantidad2']."', logica = '".$datos['logica']."', tipo_contacto_query = '".$tipo_contacto_query."', color_nombre = '".$nomColor."', color_hex = '".$hexa."', tipo_var = '".$datos['nombreContacto']."', prioridad = '".$datos['prioridad']."' WHERE id = '".$datos['idCategoria']."' ";
        $db->query($query);
    }

    public function getListarDatosCategoria($idCategoria){
        $db = new Db();
        $categoriaArray = array();
        $Sql = "SELECT * FROM SIS_Categoria_Fonos WHERE id = '".$idCategoria."'";
        $datos = $db -> select($Sql);
        foreach($datos as $dato){
        $Array = array();
        $Array['idColor'] = $dato["color"];
        $Array['idTipoContacto'] = $dato["tipo_contacto"];
        $Array['dias'] = $dato["dias"];
        $Array['condicion1'] = $dato["cond1"];
        $Array['cantidad1'] = $dato["cant1"];
        $Array['condicion2'] = $dato["cond2"];
        $Array['cantidad2'] = $dato["cant2"];
        $Array['logica'] = $dato["logica"];
        $Array['prioridad'] = $dato["prioridad"];
        array_push($categoriaArray,$Array);
        }
        return $categoriaArray;
    }
    public function deleteCategoria($id){
		$db = new DB();
		$query = "DELETE FROM SIS_Categoria_Fonos WHERE id = '".$id."'";
		$result = $db->query($query);
		return $result;
	}

}
?>    