<?php
	include_once("../includes/functions/Functions.php");
	Main_IncludeClasses("db");
	Main_IncludeClasses("reclutamiento");
	$ReclutamientoClass = new Reclutamiento();
	$ContenedoresSQL = $ReclutamientoClass->getContenedores();
	$Contenedores = array();
	foreach($ContenedoresSQL as $Contenedor){
		$Contenedores[$Contenedor["id"]] = $Contenedor["Nombre"];
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Test de Reclutamiento</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="Test de Reclutamiento CRM Sinaptica" name="description" />
		<meta content="CRM Sinaptica" name="author" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
		<link href="theme/css/plugins.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/css/layout.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/css/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
		<link href="theme/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
		<link href="../plugins/jquery_ui/jquery_ui.css" rel="stylesheet">
	</head>
	<body class="page-container-bg-solid page-header-menu-fixed">
		<div class="page-wrapper">
			<div class="page-wrapper-row">
				<div class="page-wrapper-top">
					<div class="page-header">
						<div class="page-header-top">
							<div class="container">
								<div class="page-logo">
									<a href="index.html">
										<img src="theme/img/login-invert.png" alt="logo" class="logo-default">
									</a>
								</div>
								<a href="javascript:;" class="menu-toggler"></a>
								<div class="top-menu">
									<ul class="nav navbar-nav pull-right">
										<li class="dropdown dropdown-user dropdown-dark">
											<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
												<img alt="" class="img-circle" src="../img/av1.png">
												<span class="username username-hide-mobile"><span class="nameUser">Usuario</span>  <i class="fa fa-angle-down" aria-hidden="true"></i></span>
											</a>
											<ul class="dropdown-menu dropdown-menu-default">
												<li>
													<a href="ajax/closeSession.php">
													<i class="icon-key"></i> Cerrar sesion</a>
												</li>
											</ul>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="page-header-menu">
							<div class="container">
								<div class="hor-menu">
									<ul class="nav navbar-nav">
										<li>
											<a href="actualizar_datos.php"> Actualizar Datos </a>
										</li>
										<li>
											<a href="prueba.php"> Prueba</a>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-wrapper-row full-height">
		<div class="page-wrapper-middle">
			<div class="page-container">
				<div class="page-content-wrapper">
					<div class="page-head">
						<div class="container">
							<div class="page-title">
								<h1>Actualizar Datos </h1>
							</div>
						</div>
					</div>
					<div class="page-content form-cont">
						<div class="container">
							<div class="page-content-inner">
								<div class="row">
									<div class="alert alert-success alert-dismissible display-hide" Id="Alert">
										<button type="button" class="close" id="closeAlert"><span aria-hidden="true">&times;</span></button>
										<strong>¡Bien hecho!</strong> <span id="mns"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="portlet light " id="form_wizard_1">
											<div class="portlet-title">
												<div class="caption">
													<i class=" icon-layers	"></i>
													<span class="caption-subject bold uppercase">Actualizacion de Datos
													</span>
												</div>
												<div class="actions">
													<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
												</div>
											</div>
											<div class="portlet-body form">
												<form action="#" id="submit_form" method="POST">
													<div class="form-wizard">
														<div class="form-body">
															<ul class="nav nav-pills nav-justified steps">
																<?php
																	$Cont = 1;
																	foreach($Contenedores as $Key => $Contenedor){
																		echo "<li>
																				<a href='#tab".$Key."' data-toggle='tab' class='step'>
																					<span class='number'> ".$Cont." </span>
																					<span class='desc'>
																					<i class='fa fa-check'></i> ".$Contenedor." </span>
																				</a>
																			</li>";
																		$Cont++;
																	}
																?>
															</ul>
															<div id="bar" class="progress progress-striped" role="progressbar">
																<div class="progress-bar progress-bar-success"> </div>
															</div>
															<div class="tab-content">
																<div class="alert alert-danger display-none">
																	<button class="close" data-dismiss="alert"></button> You have some form errors. Please check below.
																</div>
																<div class="alert alert-success display-none">
																	<button class="close" data-dismiss="alert"></button> Your form validation is successful!
																</div>
																<?php
																	foreach($Contenedores as $Key => $Contenedor){
																		echo "<div class='tab-pane active' id='tab".$Key."'>";
																			echo "<div class='row'>";
																			$Campos = $ReclutamientoClass->getOrdenCampos($Key);
																			foreach($Campos as $Campo){
																				$Codigo = $Campo["Codigo"];
																				$idCampo = $Campo["idCampo"];
																				$Titulo = $Campo["Titulo"];
																				$ValorEjemplo = $Campo["ValorEjemplo"];
																				$ValorPredeterminado = $Campo["ValorPredeterminado"];
																				$Tipo = $Campo["Tipo"];
																				$Dinamico = $Campo["Dinamico"];
																				$Mandatorio = $Campo["Mandatorio"];
																				$Deshabilitado = $Campo["Deshabilitado"];
																				$Anchura = $Campo["Anchura"];
																				$CampoDB = $Campo["CampoDB"];
																				
																				$Mandatorio = $Mandatorio == "1" ? " RequiredField" : "";
																				$Deshabilitado = $Deshabilitado == "1" ? " disabled " : "";
																				echo "<div class='col-md-".$Anchura."'>";
																					echo "<div class='form-group'>";
																						echo "<label class='control-label'>".$Titulo."</label>";
																						switch($Tipo){
																							case "1":
																								echo "<input type='text' class='".$Mandatorio." Field form-control' id='".$Codigo."' value='".$ValorPredeterminado."' placeholder='".$ValorEjemplo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>";
																							break;
																							case "2":
																								echo "<textarea class='".$Mandatorio." Field form-control' id='".$Codigo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>".$ValorPredeterminado."</textarea>";
																							break;
																							case "3":
																								$ToReturn = "";
																								$Opciones = $ReclutamientoClass->getOpcionesCampo($idCampo);
																								foreach($Opciones as $Opcion){
																									$Nombre = $Opcion["Nombre"];
																									$Seleccionado = $Opcion["Seleccionado"];
																									$Selected = $Seleccionado == "1" ? "selected" : "";
																									$ToReturn .= "<option ".$Selected." value='".$Nombre."' >".$Nombre."</option>";
																								}
																								echo "<select class='".$Mandatorio." Field selectpicker form-control' title='Seleccione' data-live-search='true' data-width='100%' id='".$Codigo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>".$ToReturn."</select>";
																							break;
																							case "4":
																								$ToReturn = "";
																								$Opciones = $ReclutamientoClass->getOpcionesCampo($idCampo);
																								foreach($Opciones as $Opcion){
																									$Nombre = $Opcion["Nombre"];
																									$Seleccionado = $Opcion["Seleccionado"];
																									$Selected = $Seleccionado == "1" ? "selected" : "";
																									$ToReturn .= "<option ".$Selected." value='".$Nombre."' >".$Nombre."</option>";
																								}
																								echo "<select class='".$Mandatorio." Field selectpicker form-control' title='Seleccione' data-live-search='true' data-width='100%' id='".$Codigo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."' multiple>".$ToReturn."</select>";
																							break;
																							case "5":
																								echo "<input type='text' class='".$Mandatorio." Field form-control date-picker' id='".$Codigo."' placeholder='".$ValorEjemplo."' ".$Deshabilitado." dinamico='".$Dinamico."' campodb='".$CampoDB."'>";
																							break;
																						}
																					echo "</div>";
																				echo "</div>";
																			}
																			echo "</div>";
																		echo "</div>";
																	}
																?>
																<div class="form-actions">
																	<div class="row">
																		<div class="col-md-offset-3 col-md-9">
																			<a href="javascript:;" class="btn default button-previous">
																			<i class="fa fa-angle-left"></i> Atras </a>
																			<a href="javascript:;" class="btn btn-outline green button-next"> <span>Siguiente</span>
																				<i class="fa fa-angle-right"></i>
																			</a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="page-wrapper-row">
						<div class="page-wrapper-bottom">
							<div class="page-footer">
								<div class="container">
									<?php echo date('Y'); ?> &copy; CRM Sinaptica
								</div>
							</div>
							<div class="scroll-to-top">
								<i class="icon-arrow-up"></i>
							</div>
						</div>
					</div>
				</div>
				<script src="theme/plugins/jquery.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
				<script src="theme/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
				<script src="theme/plugins/jquery.blockui.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
				<script src="theme/js/app.min.js" type="text/javascript"></script>
				<script src="theme/js/layout.min.js" type="text/javascript"></script>
				<script src="theme/js/demo.min.js" type="text/javascript"></script>
				<script src="theme/js/quick-nav.min.js" type="text/javascript"></script>
				<script src="theme/plugins/ladda/spin.min.js" type="text/javascript"></script>
				<script src="theme/plugins/ladda/ladda.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
				<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
				<script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
				<script src="../plugins/jquery_ui/jquery_ui.js"></script>
				<!-- <script src="../js/global/funciones-global.js"></script> -->
				<script src="../js/reclutamiento/controller.js"></script>
			</body>
		</html>