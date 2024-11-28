<?php
class Bot
{
    function getColas(){
		$ToReturn = array();
		$dbDiscador = new DB("discador");
		$SqlColas = "   SELECT
                            * 
                        FROM 
                            BT_config
                        WHERE
                            cartera = '".$_SESSION['cedente']."'";
		$Colas = $dbDiscador->select($SqlColas);
		if($Colas){
			foreach($Colas as $Cola){
                $ArrayTmp = array();
                $Queue = $Cola['tabla'];
				
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
                $ArrayTmp["Estado"] = $Cola["estado"];
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
            $query = "SELECT * FROM mantenedor_bot WHERE cedente = '".$cedente."'";
            $mantenedor_bot = $db->select($query);

            if($mantenedor_bot){
                $mantenedor_bot = $mantenedor_bot[0];
                $dt = new DateTime();
                $hora_actual = $dt->getTimestamp();
                $hora_inicio = strtotime($mantenedor_bot['horaInicio']);
                $hora_final = strtotime($mantenedor_bot['horaFin']);
                if($hora_actual >= $hora_inicio && $hora_actual <= $hora_final){ 
                    $focoConfig = getFocoConfig();
                    $IpServidorDiscado = $focoConfig["IpServidorDiscado"];
                    $connection = ssh2_connect($IpServidorDiscado, 22);
                    ssh2_auth_password($connection, 'root', 'Glockenspiel.,2018');
                    if($output = ssh2_exec($connection, "php /var/www/html/includes/bot/sendBOT.php '".$Cola."' > /dev/null &")) {
                        stream_set_blocking($output, true);
                        echo stream_get_contents($output);
                        $ToReturn['result'] = true;
                        $ToReturn['message'] = 'Envio de bot activado exitosamente';
                    }else{
                        $ToReturn['result'] = false;
                        $ToReturn['message'] = 'Alerta, error al activar la cola, contactar al administrador';
                    }
                }else{
                    $ToReturn['result'] = false;
                    $ToReturn['message'] = 'Alerta, El envio de bot solo sera permitido en el horario de '.$mantenedor_bot['horaInicio'].' - '.$mantenedor_bot['horaFin'];
                }
            }else{
                $ToReturn['result'] = false;
                $ToReturn['message'] = 'Alerta, horario de envio no configurado';
            }
        }else if($Estado == 0){
            $SqlSelect = "SELECT tabla FROM BT_config WHERE id = '".$Cola."'";
            $Rows = $dbDiscador->select($SqlSelect);
            if($Rows){
                foreach($Rows as $Row){
                    $Queue = $Row['tabla'];
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
            $SqlUpdate = "UPDATE BT_config SET estado = '".$Estado."' WHERE id = '".$Cola."'";
            $Update = $dbDiscador->query($SqlUpdate);
        }
        return $ToReturn;
    }
    function EliminarCola($Cola){
        $dbDiscador = new DB("discador");
        $SqlSelect = "SELECT tabla FROM BT_config WHERE id = '".$Cola."'";
        $Select = $dbDiscador->select($SqlSelect);
        if($Select){
            $Queue = $Select[0]['tabla'];
            $SqlDelete = "DROP TABLE ".$Queue;
            $Delete = $dbDiscador->query($SqlDelete);
            if($Delete){
                $SqlDelete = "DELETE FROM BT_config WHERE id = '".$Cola."'";
                $Delete = $dbDiscador->query($SqlDelete);
                return $Delete;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    function getReporte(){
        $ToReturn = array();
        $db = new DB();
        $dbDiscador = new DB("discador");
        $dbAsterisk = new DB("asterisk");
        $query = "  SELECT 
                        id as id_config, tabla, duracion
                    FROM 
                        BT_config";
        $Rows = $dbDiscador->select($query);
        if($Rows){
            foreach($Rows as $BOT){
                $id = $BOT['id_config'];
                $Queue = $BOT['tabla'];
                $duracion = $BOT['duracion'];
                $query = "  SELECT 
                                id as id_llamada, Rut, Fono
                            FROM 
                                gestion_bot
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
                                        '%BOT,".$Fono.",".$Rut."%'
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
                        $query = "UPDATE gestion_bot SET duracion = '".$billsec."', estado = '".$estado."' WHERE id = '".$id_llamada."'";
                        $db->query($query);
                    }
                }
            }
        }
    }
    // public function getEstadistica($Estrategia){
	// 	$db = new DB();
	// 	$response = array();
	// 	$response['dataSet'] = array();
	// 	$Buzon = 0;
	// 	$Bot = 0;
    //     $No_Bot = 0;
    //     $Pendiente = 0;
    //     $query = "	SELECT 
	// 					g.Rut, p.Nombre_Completo as Nombre, g.Fono, g.fecha as Fecha, g.hora as Hora, g.duracion as Duracion, g.estado as Estado
	// 				FROM 
	// 					gestion_bot g
	// 				LEFT JOIN
	// 					Persona p
	// 				ON
    //                     g.Rut = p.Rut
    //                 WHERE
    //                     g.cola = 'BOT_".$Estrategia."'";

    //     $Colas = $db->select($query);
    //     if($Colas){
    //         foreach($Colas as $Cola){
	// 			$Estado = $Cola['Estado'];
	// 			if($Estado == '0'){
	// 				$Estado = 'POSIBLE BUZON DE VOZ';
	// 				$Buzon++;
	// 			}else if($Estado == '1'){
	// 				$Estado = 'BOT CONTESTADO';
	// 				$Bot++;
	// 			}else if($Estado == '2'){
	// 				$Estado = 'BOT NO CONTESTADO';
	// 				$No_Bot++;
	// 			}else{
    //                 $Estado = 'PENDIENTE';
	// 				$Pendiente++;
    //             }
	// 			$arreglo = array();
	// 			$arreglo['Rut'] = $Cola['Rut'];
	// 			$arreglo['Nombre'] = $Cola['Nombre'];
    //             $arreglo['Fono'] = $Cola['Fono'];
    //             $arreglo['Fecha'] = $Cola['Fecha'];
    //             $arreglo['Hora'] = $Cola['Hora'];
    //             $arreglo['Duracion'] = $Cola['Duracion'];
	// 			$arreglo['Estado'] = $Estado;
	// 			array_push($response['dataSet'], $arreglo);
    //         }
	// 	}
	// 	$Total = $Buzon + $Bot + $No_Bot + $Pendiente;
	// 	$response['Total'] = $Total;
	// 	$response['Buzon'] = $Buzon;
	// 	$response['Bot'] = $Bot;
    //     $response['No_Bot'] = $No_Bot;
    //     $response['Pendiente'] = $Pendiente;
    //     return $response;        
    // }
    public function getEstadistica($Estrategia){
		$dbDiscador = new DB('discador');
		$response = array();
		$response['dataSet'] = array();
		$Buzon = 0;
        $Equivocado = 0;
        $PosibleBuzon = 0;
        $Tercero = 0;
        $Titular = 0;
        $query = "	SELECT 
						rut as Rut, fono as Fono, fecha as Fecha, hora as Hora, gestion as Gestion, respuesta_cliente as Respuesta, urlGrabacion
					FROM 
						RP_resultado_bot
                    WHERE
                        tabla = 'BOT_".$Estrategia."'
                    AND
                        gestion != 'EN PROCESO'";

        $Colas = $dbDiscador->select($query);
        if($Colas){
            foreach($Colas as $Cola){
				$Gestion = $Cola['Gestion'];
				if($Gestion == 'BUZON DE VOZ'){
					$Buzon++;
				}else if($Gestion == 'EQUIVOCADO'){
					$Equivocado++;
				}else if($Gestion == 'POSIBLE BUZON DE VOZ'){
					$PosibleBuzon++;
				}else if($Gestion == 'TERCERO'){
					$Tercero++;
				}else{
					$Titular++;
                }
				$arreglo = array();
				$arreglo['Rut'] = $Cola['Rut'];
                $arreglo['Fono'] = $Cola['Fono'];
                $arreglo['Fecha'] = $Cola['Fecha'];
                $arreglo['Hora'] = $Cola['Hora'];
                $arreglo['Gestion'] = $Cola['Gestion'];
                $arreglo['Respuesta'] = $Cola['Respuesta'];
				$arreglo['urlGrabacion'] = $Cola['urlGrabacion'];
				array_push($response['dataSet'], $arreglo);
            }
		}
		$Total = $Buzon + $Equivocado + $PosibleBuzon + $Tercero + $Titular;
		$response['Total'] = $Total;
		$response['Buzon'] = $Buzon;
        $response['Equivocado'] = $Equivocado;
        $response['PosibleBuzon'] = $PosibleBuzon;
        $response['Tercero'] = $Tercero;
        $response['Titular'] = $Titular;
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

        $sql = "SELECT * FROM mantenedor_bot WHERE cedente = '" . $cedente . "'";

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
		$sql = "SELECT id FROM mantenedor_bot WHERE cedente = '" . $cedente . "'";
		$result = $db->select($sql);

		if(count($result) > 0){
			//Existe registro para el cedente
			$sqlQuery = "UPDATE 
								mantenedor_bot
							SET horaInicio = '" . $ini . "', 
								horaFin = '" . $fin . "'
							WHERE 
								id = '" . $result[0]["id"] . "'";
		}else{
			$sqlQuery = "INSERT INTO 
								mantenedor_bot (horaInicio, horaFin, cedente) 
							VALUES ('" . $ini . "', '" . $fin . "', '" . $cedente . "')";
		}

		$query = $db->query($sqlQuery);

		if($query){
			return true;
		}else{
			return false;
		}
    }
    public function getBots(){
        $dbDiscador = new DB('discador');
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT * FROM BT WHERE Id_Cedente = '" . $cedente . "'";

        $result = $dbDiscador->select($sql);

        return $result;
    }
    public function getVoz(){
        $dbDiscador = new DB('discador');
        $cedente = $_SESSION["cedente"];

        $sql = "SELECT * FROM BT_voz WHERE FIND_IN_SET('".$cedente."',Id_Cedente)";

        $result = $dbDiscador->select($sql);

        return $result;
    }
    function testBot($id,$Nombre,$Fono){
        $ToReturn = array();
        $focoConfig = getFocoConfig();
        $IpServidorDiscado = $focoConfig["IpServidorDiscado"];
        $connection = ssh2_connect($IpServidorDiscado, 22);
        ssh2_auth_password($connection, 'root', 'Glockenspiel.,2018');
        if($output = ssh2_exec($connection, "php /var/www/html/includes/bot/testBot.php '".$id."' '".$Nombre."' '".$Fono."' > /dev/null &")) {
            stream_set_blocking($output, true);
            echo stream_get_contents($output);
            $ToReturn['result'] = true;
            $ToReturn['message'] = 'Envio de bot activado exitosamente';
        }else{
            $ToReturn['result'] = false;
            $ToReturn['message'] = 'Alerta, error al activar la cola, contactar al administrador';
        }
        return $ToReturn;
    }
}
?>