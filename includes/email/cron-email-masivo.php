<?php 
	// require("../../includes/functions/Functions.php");
	// require("../../includes/email/PHPMailer-master/class.phpmailer.php"); 
	// require("../../includes/email/PHPMailer-master/class.smtp.php"); 
	// include("../../class/email/email.php");
	// include("../../class/email/verifyEmail.php");
	// include("../../class/email/opciones.php");
	// include("../../class/db/DB.php");

	require("/var/www/html/includes/functions/Functions.php");
	require("/var/www/html/includes/email/PHPMailer-master/class.phpmailer.php");
	require("/var/www/html/includes/email/PHPMailer-master/class.smtp.php");
	include("/var/www/html/class/email/email.php");
    include("/var/www/html/class/email/verifyEmail.php");
	include('/var/www/html/class/email/opciones.php');
	include("/var/www/html/class/db/DB.php");

	$db = new Db();
	$EmailClass = new Email;
	$opciones = new opciones; 

	$query = "SELECT cantidadCorreos FROM fireConfig";
	$focoConfig = $db->select($query);
	if($focoConfig){
		$focoConfig = $focoConfig[0];
		$limite = $focoConfig['cantidadCorreos'];
		if($limite){
			$enviados = 0;

			$query = "SELECT * FROM envio_email WHERE status = 0 ORDER BY id ASC LIMIT 1";
			$pendientes = $db->select($query);

			if($pendientes){
				foreach($pendientes as $pendiente){

					$correos_array = array();

					$id_envio = $pendiente["id"];
					$asunto = $pendiente["asunto"];
					$cuerpo = $pendiente["html"];
					$cantidad = $pendiente["cantidad"];
					$offset = $pendiente["offset"];
					$estrategia = $pendiente["estrategia"];
					$adjuntar = $pendiente["adjuntar"];
					$cedente = $pendiente["Id_Cedente"];
					$template = $pendiente["template"];

					$query = "SELECT * FROM mantenedor_correo WHERE cedente = '".$cedente."'";
					$mantenedor_correo = $db->select($query);

					if($mantenedor_correo){
						$mantenedor_correo = $mantenedor_correo[0];
						$dt = new DateTime();
						$hora_actual = $dt->getTimestamp();
						$hora_inicio = strtotime($mantenedor_correo['horaInicio']);
						$hora_final = strtotime($mantenedor_correo['horaFin']);
						if($hora_actual >= $hora_inicio && $hora_actual <= $hora_final){ 
							$Conf = $opciones->configvalues($cedente,0);
							if($Conf['Email']){
								$uso_variables = $EmailClass->getVariables($cuerpo,$cedente);
								$query = "	SELECT
												rut_cliente as Rut,
												correos as Correo,
												id_gestion
											FROM
												gestion_correo
											WHERE
												id_envio = '".$id_envio."'
											AND 
												estado = 4
											AND
												correos NOT IN ( SELECT Dato FROM Exclusiones WHERE Tipo = 3 AND Id_Cedente = '".$cedente."' AND Fecha_Term >= '".date('Y-m-d ')."')";
								$correos = $db->select($query);
								foreach ($correos as $correo) {
									$query = "SELECT estatus FROM cron_email";
									$cron_email = $db->select($query);
									if($cron_email){
										$cron_email = $cron_email[0];
										$estatus_cron = $cron_email['estatus'];
									}else{
										$estatus_cron = 0;
									}
									if($estatus_cron == 1){
										$query = "SELECT status FROM envio_email WHERE id = '".$id_envio."'";
										$envio_email = $db->select($query);

										if($envio_email){
											$envio_email = $envio_email[0];
											$estatus_envio = $envio_email['status'];
										}else{
											$estatus_envio = '-1';
										}
										if($estatus_envio == 0){
											if($enviados < $limite){
												$email = $correo['Correo'];
												if($email){
													$info = array();
													$adjuntos = array();
													if($EmailClass->checkEmail($email)){
														$rut = $correo['Rut'];
														$info[$email]["Rut"] = $rut;

														//Obtener valor de cada Variable para cada rut
														if($uso_variables){
															foreach ($uso_variables as $var){
																$info[$email][$var] = $EmailClass->get_var_value($rut,$var,$cedente);
															}
														}
														
														//Consultar adjuntos
														if($adjuntar == 1){
															$query = "  SELECT 
																			Numero_Factura 
																		FROM 
																			Deuda 
																		WHERE 
																			Rut = '".$rut."' 
																		AND 
																			Id_Cedente = '".$cedente."'";

															$deudas = $db->select($query); 

															if($deudas) {
																$facturas = array();
																foreach($deudas as $deuda){
																	$facturas[] = $deuda['Numero_Factura'];
																}
																$adjuntos[$email] = $facturas;
															}
														}else {
															$adjuntos = false;
														}

														$info['adjuntos'] = $adjuntos;
														$info['variables'] = $uso_variables;

														$envio = $EmailClass->SendMail($cuerpo, $asunto, $email, $info, $cedente,"0");
														if($envio){		
															$estado = '1';
															$enviados++;	
															sleep(7);										
														}else{
															$estado = '6';
														}
													}else{
														$estado = '2';
													}
													
													$id_gestion = $correo['id_gestion'];
													$query = "UPDATE gestion_correo SET estado = '".$estado."', fecha_gestion = NOW(), hora_gestion = NOW() WHERE id_gestion = '".$id_gestion."'";
													$update = $db->query($query);
													$offset++;
												}
											}else{
												echo 'CANTIDAD DE ENVIOS HA LLEGADO A SU LIMITE';
												break;
											}
										}else{
											echo 'COLA DETENIDA, STATUS ' . $estatus_envio . ' COLA ' . $id_envio;
											break;
										}							
									}else{
										echo 'CRONJOB DETENIDO';
										break;
									}
								}
							}else{
								echo 'CORREO DE ENVIO NO CONFIGURADO PARA CEDENTE ' . $cedente;
							}
						}else{
							echo 'HORARIO FUERA DE LIMITE PARA CEDENTE ' . $cedente;
						}
					}else{
						echo 'MANTENEDOR CORREO NO CONFIGURADO PARA CEDENTE ' . $cedente;
					}

					$query = "	SELECT
									id_gestion
								FROM
									gestion_correo
								WHERE
									id_envio = '".$id_envio."'
								AND 
									estado = 4
								AND
									correos NOT IN ( SELECT Dato FROM Exclusiones WHERE Tipo = 3 AND Id_Cedente = '".$cedente."' AND Fecha_Term >= CURDATE())";
					$correos = $db->select($query);
					if($correos){
						$status = '0';
					}else{
						$status = '1';
					}
					$query = "UPDATE envio_email SET status = '".$status."', offset = '".$offset."' WHERE id = '".$id_envio."'";
					$update = $db->query($query);
					if(isset($estatus_cron)){
						if($estatus_cron == 0){
							break;
						}
					}
				}
			}else{
				echo 'NO HAY ENVIOS PENDIENTES';
			}
		}else{
			echo 'CANTIDAD DE CORREOS NO CONFIGURADOS EN fireConfig';
		}
	}else{
		echo 'FOCOCONFIG NO CONFIGURADO';
	}
?>








