<?php
class Ivr
{
    function getColas(){
		$ToReturn = array();
		$dbDiscador = new DB("discador");
		$SqlColas = "   SELECT
                            * 
                        FROM 
                            ivrConfig
                        WHERE
                            Cedente = '".$_SESSION['cedente']."'";
		$Colas = $dbDiscador->select($SqlColas);
		if($Colas){
			foreach($Colas as $Cola){
                $ArrayTmp = array();
                $Queue = $Cola['cola'];
				
				$SqlCantRuts = "select count(*) as Cantidad from (select count(*) as CantidadTmp from ".$Queue." group by Rut) tb1";
				$CantRuts = $dbDiscador->select($SqlCantRuts);
				$CantRuts = $CantRuts[0]["Cantidad"];

				$SqlCantRutsLlamados = "select count(*) as Cantidad from (select count(*) as CantidadTmp from ".$Queue." Where llamado='1' group by Rut) tb1";
				$CantRutsLlamados = $dbDiscador->select($SqlCantRutsLlamados);
				if(count($CantRutsLlamados) > 0){
					$CantRutsLlamados = $CantRutsLlamados[0]["Cantidad"];
				}else{
					$CantRutsLlamados = "0";
				}

				$SqlCantFonos = "select count(*) as Cantidad from ".$Queue;
				$CantFonos = $dbDiscador->select($SqlCantFonos);
				$CantFonos = $CantFonos[0]["Cantidad"];

				$SqlCantFonosLlamados = "select count(*) as Cantidad from ".$Queue." where llamado = '1' ";
				$CantFonosLlamados = $dbDiscador->select($SqlCantFonosLlamados);
				$CantFonosLlamados = $CantFonosLlamados[0]["Cantidad"];

                $ArrayTmp["Nombre"] = $Cola["nombre"];
                $ArrayTmp["Canales"] = $Cola["canales"];
                $ArrayTmp["Estado"] = $Cola["estatus"];
				$ArrayTmp["ProgresoRuts"] = $CantRutsLlamados."/".$CantRuts;
				$ArrayTmp["ProgresoFonos"] = $CantFonosLlamados."/".$CantFonos;
				$ArrayTmp["Accion"] = $Cola["id"];
				array_push($ToReturn,$ArrayTmp);
			}
		}
		return $ToReturn;
    }
    function CambiarStatusCola($Cola,$Estado){
        $dbDiscador = new DB("discador");
        $ToReturn = array();
        if($Estado == 1){
            $cedente = $_SESSION['cedente'];
            $db = new DB();
            $query = "SELECT * FROM mantenedor_ivr WHERE cedente = '".$cedente."'";
            $mantenedor_ivr = $db->select($query);

            if($mantenedor_ivr){
                $mantenedor_ivr = $mantenedor_ivr[0];
                $dt = new DateTime();
                $hora_actual = $dt->getTimestamp();
                $hora_inicio = strtotime($mantenedor_ivr['horaInicio']);
                $hora_final = strtotime($mantenedor_ivr['horaFin']);
                if($hora_actual >= $hora_inicio && $hora_actual <= $hora_final){ 
                    $focoConfig = getFocoConfig();
                    $IpServidorDiscado = $focoConfig["IpServidorDiscado"];
                    $connection = ssh2_connect($IpServidorDiscado, 22);
                    ssh2_auth_password($connection, 'root', 'Glockenspiel.,2018');
                    if($output = ssh2_exec($connection, "php /var/www/html/includes/ivr/sendIVR.php '".$Cola."' > /dev/null &")) {
                        stream_set_blocking($output, true);
                        echo stream_get_contents($output);
                        $ToReturn['result'] = true;
                        $ToReturn['message'] = 'Envio de ivr activado exitosamente';
                    }else{
                        $ToReturn['result'] = false;
                        $ToReturn['message'] = 'Alerta, error al activar la cola, contactar al administrador';
                    }
                }else{
                    $ToReturn['result'] = false;
                    $ToReturn['message'] = 'Alerta, El envio de ivr solo sera permitido en el horario de '.$mantenedor_ivr['horaInicio'].' - '.$mantenedor_ivr['horaFin'];
                }
            }else{
                $ToReturn['result'] = false;
                $ToReturn['message'] = 'Alerta, horario de envio no configurado';
            }
        }else if($Estado == 0){
            $SqlSelect = "SELECT cola FROM ivrConfig WHERE id = '".$Cola."'";
            $Rows = $dbDiscador->select($SqlSelect);
            if($Rows){
                foreach($Rows as $Row){
                    $Queue = $Row['cola'];
                    $SqlUpdate = "UPDATE ".$Queue." SET llamado = '0'";
                    $Update = $dbDiscador->query($SqlUpdate);
                }
            }
            $ToReturn['result'] = true;
            $ToReturn['message'] = 'Envio detenido exitosamente';
        }else{
            $ToReturn['result'] = true;
            $ToReturn['message'] = 'Envio pausado exitosamente';
        }
        if($ToReturn['result']){
            $SqlUpdate = "UPDATE ivrConfig SET estatus = '".$Estado."' WHERE id = '".$Cola."'";
            $Update = $dbDiscador->query($SqlUpdate);
        }
        return $ToReturn;
    }
    function EliminarCola($Cola){
        $focoConfig = getFocoConfig();
        $IpServidorDiscado = $focoConfig["IpServidorDiscado"];
        $CodigoFoco = $focoConfig["CodigoFoco"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".$IpServidorDiscado."/includes/ivr/deleteAudio.php");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'CodigoFoco'    => $CodigoFoco,
            'id_config'     => $Cola
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        if($result){
            $dbDiscador = new DB("discador");
            $SqlSelect = "SELECT cola FROM ivrConfig WHERE id = '".$Cola."'";
            $Select = $dbDiscador->select($SqlSelect);
            if($Select){
                $Queue = $Select[0]['cola'];
                $SqlDelete = "DROP TABLE ".$Queue;
                $Delete = $dbDiscador->query($SqlDelete);
                if($Delete){
                    $SqlDelete = "DELETE FROM ivrConfig WHERE id = '".$Cola."'";
                    $Delete = $dbDiscador->query($SqlDelete);
                    return $Delete;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    function getReporte(){
        $ToReturn = array();
        $db = new DB();
        $dbDiscador = new DB("discador");
        $dbAsterisk = new DB("asterisk");
        $query = "  SELECT 
                        id as id_config, cola, duracion
                    FROM 
                        ivrConfig";
        $Rows = $dbDiscador->select($query);
        if($Rows){
            foreach($Rows as $IVR){
                $id = $IVR['id_config'];
                $Queue = $IVR['cola'];
                $duracion = $IVR['duracion'];
                $query = "  SELECT 
                                id as id_llamada, Rut, Fono
                            FROM 
                                gestion_ivr
                            WHERE
                                cola = '".$Queue."'
                            AND
                                estado = 3
                            -- AND
                            --     TIMESTAMPDIFF(day, fecha, NOW()) = 0
                            ";
                $Gestiones = $db->select($query);
                if($Gestiones){
                    foreach($Gestiones as $Gestion){
                        $id_llamada = $Gestion['id_llamada'];
                        $Rut = $Gestion['Rut'];
                        $Fono = $Gestion['Fono'];
                        $query = "  SELECT
                                        billsec 
                                    FROM 
                                        cdr.cdr
                                    WHERE
                                        clid 
                                    LIKE 
                                        '%IVR,".$Fono.",".$Rut."%'
                                    AND
                                        accountcode = '".$id."'
                                    ORDER BY
	                                    calldate DESC";
                        $cdr = $dbAsterisk->select($query);
                        if($cdr){
                            $billsec = $cdr[0]['billsec'];
                            if($billsec == 5 OR $billsec <= 0){
                                $estado = '2';
                            }else{
                                $estado = '1';
                            }
                        }else{
                            $estado = '0';
                            $billsec = 0;
                        } 
                        $query = "UPDATE gestion_ivr SET duracion = '".$billsec."', estado = '".$estado."' WHERE id = '".$id_llamada."'";
                        $db->query($query);
                    }
                }
            }
        }
    }
    public function getEstadistica($Estrategia){
		$db = new DB();
		$response = array();
		$response['dataSet'] = array();
		$Buzon = 0;
		$Ivr = 0;
        $No_Ivr = 0;
        $Pendiente = 0;
        $query = "	SELECT 
						g.Rut, p.Nombre_Completo as Nombre, g.Fono, g.fecha as Fecha, g.hora as Hora, g.duracion as Duracion, g.estado as Estado
					FROM 
						gestion_ivr g
					LEFT JOIN
						Persona p
					ON
                        g.Rut = p.Rut
                    WHERE
                        g.cola = 'IVR_".$Estrategia."'";

        $Colas = $db->select($query);
        if($Colas){
            foreach($Colas as $Cola){
				$Estado = $Cola['Estado'];
				if($Estado == '2'){
					$Estado = 'POSIBLE BUZON DE VOZ';
					$Buzon++;
				}else if($Estado == '1'){
					$Estado = 'IVR CONTESTADO';
					$Ivr++;
				}else if($Estado == '0'){
					$Estado = 'IVR NO CONTESTADO';
					$No_Ivr++;
				}else{
                    $Estado = 'PENDIENTE';
					$Pendiente++;
                }
				$arreglo = array();
				$arreglo['Rut'] = $Cola['Rut'];
				$arreglo['Nombre'] = $Cola['Nombre'];
                $arreglo['Fono'] = $Cola['Fono'];
                $arreglo['Fecha'] = $Cola['Fecha'];
                $arreglo['Hora'] = $Cola['Hora'];
                $arreglo['Duracion'] = $Cola['Duracion'];
				$arreglo['Estado'] = $Estado;
				array_push($response['dataSet'], $arreglo);
            }
		}
		$Total = $Buzon + $Ivr + $No_Ivr + $Pendiente;
		$response['Total'] = $Total;
		$response['Buzon'] = $Buzon;
		$response['Ivr'] = $Ivr;
        $response['No_Ivr'] = $No_Ivr;
        $response['Pendiente'] = $Pendiente;
        return $response;        
    }
    
    /*********************************************************************
    ** getHorasCorreo (Obtener horario de envío Correo para el cedente) **
    **  Parámetros                                                      **
    **  Return                                                          **
    **      Consulta con el horario de envío de Correo                  **
    **********************************************************************/
    public function getHoras(){
        $db = new DB();
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT * FROM mantenedor_ivr WHERE cedente = '" . $cedente . "'";

        $result = $db->select($sql);

        return $result;
	}
	
	
	/*************************************************************************
    ** guardarMantenedorCorreo (Guardar configuración de envío de Correos) 	**
    **  Parámetros                                                      	**
    **      Horario Inicio, Horario Fin, Cantidad Cedente 				   	**
    **  Return                                                         	 	**
    **      TRUE:FALSE                                                  	**
    **************************************************************************/
    public function guardarMantenedor($ini, $fin){
        $db = new DB();
		$cedente = $_SESSION["cedente"];
		$sql = "SELECT id FROM mantenedor_ivr WHERE cedente = '" . $cedente . "'";
		$result = $db->select($sql);

		if(count($result) > 0){
			//Existe registro para el cedente
			$sqlQuery = "UPDATE 
								mantenedor_ivr
							SET horaInicio = '" . $ini . "', 
								horaFin = '" . $fin . "'
							WHERE 
								id = '" . $result[0]["id"] . "'";
		}else{
			$sqlQuery = "INSERT INTO 
								mantenedor_ivr (horaInicio, horaFin, cedente) 
							VALUES ('" . $ini . "', '" . $fin . "', '" . $cedente . "')";
		}

		$query = $db->query($sqlQuery);

		if($query){
			return true;
		}else{
			return false;
		}
	}
}
?>