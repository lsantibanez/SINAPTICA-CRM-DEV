<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "modSup,supMonitoreo"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
	//to fully log out a visitor we need to clear the session varialbles
	$objetoSession->borrarVariablesSession();
	$objetoSession->logoutGoTo("../index.php");
}

$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];

if(isset($_SESSION['cedente'])){
	if($_SESSION['cedente'] != ""){
		$cedente = $_SESSION['cedente'];
	}
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>CRM Sinaptica | Software de Estrategia</title>
		<!--STYLESHEET-->
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/nifty.min.css" rel="stylesheet">
		<link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
		<link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
		<link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
		<link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
		<link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
		<link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
		<link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
		<link href="../plugins/pace/pace.min.css" rel="stylesheet">
		<script src="../plugins/pace/pace.min.js"></script>
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
		<link href="../css/global/global.css" rel="stylesheet">
		<style type="text/css">
			.greenBG{
				background-color: #449d44;
				border-color: #4cae4c;
				color: white;
				font-size: 14px;
				font-weight: bold;
			}
			.purpleBG{
				background-color: #563d7c;
				color: white;
				font-size: 14px;
				font-weight: bold;
			}
			.lightblueBG{
				background-color: #5bc0de;
				border-color: #46b8da;
				color: white;
				font-size: 14px;
				font-weight: bold;
			}
			.blueBG{
				background-color: #337ab7;
				border-color: #2e6da4;
				color: white;
				font-size: 14px;
				font-weight: bold;
			}
			.redBG{
				background-color: #d9534f;
    			border-color: #d43f3a;
				color: white;
				font-size: 14px;
				font-weight: bold;
			}
			.yellowBG{
				background-color: #f0ad4e;
    			border-color: #eea236;
				color: white;
				font-size: 14px;
				font-weight: bold;
			}
			.iconos, .verContactabilidad{
				color: white;
				text-align: center; 
				padding: 5px 0px;
				width: 35px; 
				height: 35px;
			}
			.modalEstrategias > .modal-dialog {
				width:70% !important;
			}
			body {
				padding-right: 0 !important;
			}
			.numeros{
				font-size: 18px;
				padding-left: 5px;
			}
			.resumenCampana{
				font-size: 36px;
				color: mediumslateblue;
			}
			.lightBlue{
				background-color: lightblue !important;
			}
			#mostrar_puestos_trabajo tr td {
				padding: 2px 3px !important;
				font-size: 10px !important;
			}
			#agentesPorEstatus tr td {
				padding: 2px 3px !important;
			}
			.franja{
				height: 25px;
				width: 50%;
				margin: 0 auto;
				color: white;
			}
			.align{
				display: inline-block;
    			padding-top: 5px;
			}
			.disponible1{ background-color: #B39FD0 !important;}
			.disponible2{ background-color: #563d7c !important; color: white;}
			.disponible3{ background-color: #4B09AE !important; color: white;}
			.hablando1{ background-color: #AFCEE9 !important;}
			.hablando2{ background-color: #337AB7 !important; color: white;}
			.hablando3{ background-color: #1F4A6F !important; color: white;}
			.pausado1{ background-color: #F8DCB4 !important;}
			.pausado2{ background-color: #eea236 !important; color: white;}
			.pausado3{ background-color: #965D0D !important; color: white;}
			.black{ background-color: #000000 !important; color: white;}
			.popover {
				background: transparent;
				border: none;
				box-shadow: none;
			}
		</style>
	</head>
	<body>
		<input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>">
		<input type="hidden" name="interval" id="interval" value="30000">
		<div id="container" class="effect mainnav-lg">
			<!--NAVBAR-->
			<!--===================================================-->
			<?php include("../layout/header.php"); ?>
			<!--===================================================-->
			<!--END NAVBAR-->
			<div class="boxed">
				<!--CONTENT CONTAINER-->
				<!--===================================================-->
				<div id="content-container">
					<!--Page Title-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div id="page-title">
						<h1 class="page-header text-overflow">Supervisión Discador Predictivo</h1>
					<!--Searchbox-->
					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End page title-->
					<!--Breadcrumb-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<ol class="breadcrumb">
						<li><a href="#">Supervisión</a></li>
						<li class="active">Supervisión Discador Predictivo</li>
					</ol>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End breadcrumb-->
					<!--Page content-->
					<!--===================================================-->
					<div id="page-content">
						<div class="row">
							<div class="col-sm-12 text-center">
								<label><b>Modo Monitor</b></label>
								<br>
								<input id='estadistica-switch' name='estadistica-switch' class='toggle-switch' type='checkbox'>
								<label class='toggle-switch-label'></label>
								<br>
								<br>
							</div>
						</div>
						<div id="estadistica">
							<div class="row">
								<div class="col-sm-2">
									<!-- <div class="panel greenBG">
										<div class="panel-body">
											<div class="col-sm-12 text-center">
												<i class="fa fa-phone-square fa-lg"></i>
												<span id="spanLlamar">123354</span>
												<span>LLAMAR</span>
											</div>
										</div>
									</div> -->
									<div class="panel purpleBG" style="display: none;">
										<div class="panel-body">
											<div class="col-sm-12 text-center">
												<i class="fa fa-list fa-lg"></i>
												<span id="spanCola"></span>
												<span> EN COLA</span>
											</div>
										</div>
									</div>
									<div class="panel lightblueBG" style="display: none;">
										<div class="panel-body">
											<div class="col-sm-12 text-center">
												<i class="fa fa-tty fa-lg"></i>
												<span id="spanCont"></span>
												<span> CONTACTA. %</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-8">
									<div class="panel">
										<div class="panel-body">
											<div class="col-sm-10 text-center">
												<div class="col-sm-2" style="padding-top: 6px;">
													<span class="pull-left"><i class="fa fa-th-list fa-2x estrategias" style="cursor: pointer"></i></span>
												</div>
												<div class="col-sm-10" style="padding-top: 8px; font-size: 15px">
													<span style="font-weight: bold" id="tituloEstrategias"></span>
												</div>
											</div>
											<div class="col-sm-2" style="padding-top: 3px;">
												<span class="pull-right"><i class="fa fa-cog fa-2x configuracion" style="cursor: pointer"></i></span>
											</div>
										</div>
										<div class="panel-body">
											<div class="form-group">
												<!-- <div class="col-sm-3" style="text-align: center">
													<div class="col-sm-12">LLAMADAS RINGUEANDO</div>
												</div> -->
												<div class="col-sm-6" style="text-align: center">
													<span class="resumenCampana" id="agentesDisponibles"></span>
													<div class="col-sm-12">AGENTES DISPONIBLES</div>
												</div>
												<div class="col-sm-6" style="text-align: center">
													<span class="resumenCampana" id="hablandoConectados"></span>
													<div class="col-sm-12">HABLANDO/CONECTADOS</div>
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="form-group">
												<!-- <div class="col-sm-3" style="text-align: center">
													LLAMADAS RINGUEANDO
												</div> -->
												<div class="col-sm-6" style="text-align: center">
													<div class="col-sm-12" id="countDown"></div>
												</div>
												<div class="col-sm-6" style="text-align: center">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="panel blueBG" style="display: none;">
										<div class="panel-body">
											<div class="col-sm-12 text-center">
												<i class="fa fa-cogs fa-lg"></i>
												<span id="spanHoy"></span>
												<span> HOY</span>
											</div>
										</div>
									</div>
									<div class="panel redBG" style="display: none;">
										<div class="panel-body">
											<div class="col-sm-12 text-center descartadas" style="cursor: pointer">
												<i class="fa fa-trash-o fa-lg"></i>
												<span id="spanDes"></span>
												<span> DESCARTADAS</span>
											</div>
										</div>
									</div>
									<div class="panel yellowBG" style="display: none;">
										<div class="panel-body">
											<div class="col-sm-12 text-center">
												<i class="fa fa-check-square-o fa-lg"></i>
												<span id="spanPen"></span>
												<span> PENETRACIÓN %</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="eq-height">
									<div class="col-sm-12">
										<div class="panel">
											<div class="panel-body">
												<div class="col-sm-2 text-center">
													<div style="vertical-align: middle">
														<span style="float: left; inline-size: auto">
															<button class="btn" id="CONECTADOS" style="background-color: #449d44;">
																<i class="fa fa-plug fa-2x"></i>
															</button>
															<span class="numeros" id="conectados"></span> CONECTADOS
														</span>
													</div>
												</div>
												<div class="col-sm-2 text-center">
													<div style="vertical-align: middle">
														<span style="float: left; inline-size: auto">
															<button class="btn iconos" id="EN LLAMADA" style="background-color: #563d7c;">
																<i class="fa fa-headphones fa-2x"></i>
															</button>
															<span class="numeros" id="hablando"></span> HABLANDO
														</span>
													</div>
												</div>
												<div class="col-sm-2 text-center">
													<div style="vertical-align: middle">
														<span style="float: left; inline-size: auto">
															<button class="btn iconos" id="DISPONIBLE" style="background-color: #337ab7;">
																<i class="fa fa-meh-o fa-2x"></i>
															</button>
															<span class="numeros" id="disponible"></span> DISPONIBLE
														</span>
													</div>
												</div>
												<div class="col-sm-2 text-center">
													<div style="vertical-align: middle">
														<span style="float: left; inline-size: auto">
															<button class="btn iconos" id="PAUSADO" style="background-color: #eea236;">
																<i class="fa fa-pause fa-2x"></i>
															</button>
															<span class="numeros" id="pausados"></span> PAUSADOS
														</span>
													</div>
												</div>
												<div class="col-sm-2 text-center">
													<div style="vertical-align: middle">
														<span style="float: left; inline-size: auto">
															<button class="btn iconos" id="MUERTO" style="background-color: #000000;">
																<i class="fa fa-user-md fa-2x"></i>
															</button>
															<span class="numeros" id="dead"></span> MUERTO
														</span>
													</div>
												</div>
												<div class="col-sm-2 text-center">
													<div style="vertical-align: middle; display: none" id="divContacta">
														<span style="float: left; inline-size: auto">
															<button class="btn verContactabilidad" style="background-color: #d9534f;">
																<i class="fa fa-pie-chart fa-2x"></i>
															</button> VER CONTACTABILIDAD
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="eq-height">
								<div class="col-sm-12">
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-3">
													<div class="form-group">
														<div class="input-daterange input-group " id="datepicker">
															<label for="Id_Mandante">Seleccione Mandante</label>
															<select name="Id_Mandante" id="Id_Mandante" class="selectpicker form-control" data-live-search="true" data-width="100%" title="Seleccione Mandante">
																<option value = ''>Todos</option>
																<?php
																	$db = new DB();
																	$rows = $db->select("SELECT id,nombre FROM mandante where estatus = '1' ORDER BY nombre ASC");
																	foreach($rows as $row){
																	?>
																		<option value = '<?php echo $row["id"];?>'><?php echo $row["nombre"];?></option>
																	<?php
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group">
														<label for="Id_Cedente">Seleccione Cedente</label>
														<select name="Id_Cedente" id="Id_Cedente" class="selectpicker form-control" data-live-search="true" data-width="100%" title="Seleccione Cedente">
															<option value = ''>Todos</option>
														</select>
													</div>
												</div>
											</div>
											<div class="table-responsive">
												<table id="mostrar_puestos_trabajo" class="cell-border" style="width:100%">
													<thead>
														<tr>
															<th>Puesto</th>
															<th>Ejecutivo</th>
															<th>Estado</th>
															<th>Pausa</th>
															<th>Tiempo de Conexión</th>
															<th>Cartera</th>
															<th>Campaña</th>
															<th>Quitar Agente</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="eq-height">
								<div class="col-sm-12">
									<div class="panel">
										<div class="panel-heading">
											<h2 class="panel-title">Leyenda</h2>
										</div>
										<div class="panel-body">
											<div class="col-sm-3 text-center">
												<div style="vertical-align: middle; padding-bottom: 5px;">
													<i class="fa fa-headphones fa-lg"></i>  HABLANDO
												</div>
												<div class="franja align" style="background-color: #B39FD0; color: black;">
													<span style="vertical-align: middle;">
														<i class="fa fa-minus"></i> Menos de 1 minuto
													</span>
												</div>
												<div class="franja align" style="background-color: #563d7c;">
													<span style="vertical-align: middle;">
														<i class="fa fa-plus"></i> Más de 1 minuto
													</span>
												</div>
												<div class="franja align" style="background-color: #4B09AE;">
													<span style="vertical-align: middle;">
														<i class="fa fa-plus"></i> Más de 5 minutos
													</span>
												</div>
											</div>
											<div class="col-sm-3 text-center">
												<div style="vertical-align: middle; padding-bottom: 5px;">
													<i class="fa fa-meh-o fa-lg"></i>  DISPONIBLE
												</div>
												<div class="franja align" style="background-color: #AFCEE9; color: black;">
													<span style="vertical-align: middle;">
														<i class="fa fa-minus"></i> Menos de 1 minuto
													</span>
												</div>
												<div class="franja align" style="background-color: #337AB7;">
													<span style="vertical-align: middle;">
														<i class="fa fa-plus"></i> Más de 1 minuto
													</span>
												</div>
												<div class="franja align" style="background-color: #1F4A6F;">
													<span style="vertical-align: middle;">
														<i class="fa fa-plus"></i> Más de 5 minutos
													</span>
												</div>
											</div>
											<div class="col-sm-3 text-center">
												<div style="vertical-align: middle; padding-bottom: 5px;">
													<i class="fa fa-pause fa-lg"></i>  PAUSADOS
												</div>
												<div class="franja align" style="background-color: #F8DCB4; color: black;">
													<span style="vertical-align: middle;">
														<i class="fa fa-minus"></i> Menos de 1 minuto
													</span>
												</div>
												<div class="franja align" style="background-color: #eea236;">
													<span style="vertical-align: middle;">
														<i class="fa fa-plus"></i> Más de 1 minuto
													</span>
												</div>
												<div class="franja align" style="background-color: #965D0D;">
													<span style="vertical-align: middle;">
														<i class="fa fa-plus"></i> Más de 5 minutos
													</span>
												</div>
											</div>
											<div class="col-sm-3 text-center">
												<div style="vertical-align: middle; padding-bottom: 5px;">
													<i class="fa fa-user-md fa-lg"></i>  MUERTO
												</div>
												<div class="franja align" style="background-color: #000000;">
													<span style="vertical-align: middle;">
														Llamada Finalizada
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--===================================================-->
					<!--End page content-->
				</div>
				<!--===================================================-->
				<!--END CONTENT CONTAINER-->
				<!--MAIN NAVIGATION-->
				<!--===================================================-->
				<?php include("../layout/main-menu.php"); ?>
				<!--===================================================-->
				<!--END MAIN NAVIGATION-->
				<div class="modal fade" tabindex="-1" role="dialog" id="Cargando">
					<div class="modal-dialog modal-sm" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="spinner loading"></div>
									<h4 class="text-center">Procesando por favor espere...</h4>
								</div>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</div>
			<!-- FOOTER -->
			<!--===================================================-->
			<?php include("../layout/footer.php"); ?>
			<!--===================================================-->
			<!-- END FOOTER -->
			<!-- SCROLL TOP BUTTON -->
			<!--===================================================-->
			<button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
			<div class="modal"><!-- Place at bottom of page --></div>
			<!--===================================================-->
		</div>
			<!--===================================================-->
			<!-- END OF CONTAINER -->
		<script id="modalEstrategias" type="text/template">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Seleccione Cola:</label>
								<select class="selectpicker form-control" id="Colas" name="Colas" title="Seleccione" data-live-search="true" data-width="100%">
								</select>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</script>
		<script id="modalAgentes" type="text/template">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <table id="agentesPorEstatus" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Puesto</th>
									<th>Ejecutivo</th>
									<th>Estado</th>
									<th>Pausa</th>
									<th>Tiempo de Conexión</th>
									<th>Llamadas</th>
									<th>Campaña</th>
									<th>Hold</th>
									<th>In-Group</th>
									<th>Quitar Agente</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
		</script>
		<script id="modalConfiguracion" type="text/template">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Tiempo de Refresco:</label>
								<select class="selectpicker form-control" id="Tiempo" name="Tiempo" title="Seleccione" data-live-search="true" data-width="100%">
									<option value="30">30 segundos</option>
									<option value="60">1 minuto</option>
									<option value="300">5 minutos</option>
									<option value="600">10 minutos</option>
								</select>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</script>
		<script id="modalDescartadas" type="text/template">
            <div class="row">
                <canvas id="descartadasChart"></canvas>
            </div>
		</script>
		<!--JAVASCRIPT-->
		<script src="../js/jquery-2.2.1.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../plugins/fast-click/fastclick.min.js"></script>
		<script src="../js/nifty.min.js"></script>
		<script src="../plugins/morris-js/morris.min.js"></script>
		<script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
		<script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
		<script src="../plugins/skycons/skycons.min.js"></script>
		<script src="../plugins/switchery/switchery.min.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../js/demo/nifty-demo.min.js"></script>
		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../js/demo/ui-alerts.js"></script>
		<script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
		<script src="../js/global/funciones-global.js"></script>
		<script src="../plugins/Chart.js/Chart.min.js"></script>
		<script src="../plugins/Chart.js/Chart.bundle.min.js"></script>
		<script src="../js/supervision/supervisionMonitoreo.js"></script>
	</body>
</html>