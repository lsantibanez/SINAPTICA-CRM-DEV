<?php
	require_once('../class/db/DB.php');
    require_once('../class/session/session.php');

	include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

    // $objetoSession = new Session($Permisos,false); // 1,4
    $objetoSession = new Session('1,2,3,4,5,6',false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "cliSer,serVer"));
    // ** Logout the current user. **
    $objetoSession->creaLogoutAction();
    if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
    {
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
	    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
	    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
	    <link href="../css/global/global.css" rel="stylesheet">
	</head>
	<body>
		<div id="container" class="effect mainnav-lg">    
	        <!--NAVBAR-->
	        <!--===================================================-->
	        <?php
	        	include("../layout/header.php");
	        ?>
	        <!--===================================================-->
	        <!--END NAVBAR-->

	        <div class="boxed">

	            <!--CONTENT CONTAINER-->
	            <!--===================================================-->
	            <div id="content-container">
	                
	                <!--Page Title-->
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <div id="page-title">
	                    <h1 class="page-header text-overflow">Modulo Servicios</h1>
	                    <!--Searchbox-->
	            
	                </div>
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <!--End page title-->


	                <!--Breadcrumb-->
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <ol class="breadcrumb">
	                    <li><a href="#">Servicios</a></li>
	                    <li class="active">Ver Servicios</li>
	                </ol>
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <!--End breadcrumb-->


	                <!--Page content-->
	                <!--===================================================-->
					<div id="page-content">
						<div class="row">
							<div class="col-md-12">
								<div class="panel ">
									<!--Panel heading-->
									<div class="panel-heading">
										<h3 class="panel-title">Visualización de servicios de clientes</h3>
									</div>
									<!--Panel body-->
									<div class="panel-body">
										<!--Tabs content-->
										<div class="tab-content">
											<h3>Lista de Clientes</h3><br>
											<div class="row">
												<div class="col-md-4">
													<select name="Rut" class="form-control" data-live-search="true">
														<option value="">Seleccione...</option>
													</select>
												</div>
											</div>
											<br><br>
											<div class="row">
												<div class="panel">
													<!--Panel heading-->
													<div class="panel-heading">
														<div class="panel-control" style="float: left;">
															<ul class="nav nav-tabs">
																<h4>Servicios</h4>
															</ul>
														</div>
														<h3 class="panel-title">&nbsp;</h3>
													</div>
													<!--Panel body-->
													<div class="panel-body">
														<div class="tab-content">

															<div class="dataServicios">
															<h4>No hay información</h4>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

            
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <?php include("../layout/main-menu.php"); ?> 
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
            

        </div>

        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->


		<div class="modal fade" tabindex="-1" role="dialog" id="verServicios" aria-labelledby="verServicios">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Lista de Datos técnicos</h4>
					</div>
					<div class="modal-body containerListDatosTecnicos">
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="agregarDatosTecnicos">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Agregar Datos Técnicos</h4>
					</div>
					<div class="modal-body containerTipoServicio">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary guardarDatosTecnicos">Guardar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div id="modalEditar" class="modal fade" tabindex="-1" role="dialog" id="load">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
						<h4 class="modal-title c-negro">Editar Servicio <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
					</div>
					<div class="modal-body">
						<form id = "showServicio">
							<input type="hidden" name="Id" id="Id">
							<div class="row" style="padding:20px">
								<div class="col-md-12">
									<div class="form-group">
										<label class="compo-grupo">Grupo</label>
										<div class="compo-grupo">
											<select id="Grupo" name="Grupo" class="form-control selectpicker" data-live-search="true">
												<option value="">Seleccione...</option>
											</select>
										</div>
										<br>
										<div class="form-group">
											<label>Tipo de Servicio</label>
											<select name="TipoServicio" id="TipoServicio" class="form-control selectpicker" data-live-search="true" validation="not_null" data-nombre="Servicio">
												<option value="">Seleccione...</option>
											</select>
										</div>
										<br>
										<label class="campo-Valor">Valor</label>
										<div class="form-group">
											<input id="Valor" type="text"  name="Valor" class="form-control">
										</div>
										<br>
										<label>Descuento</label>
										<div class="input-group">
											<input type="text" id="Descuento" name="Descuento" class="form-control">
											<span class="input-group-addon">%</span>
										</div>
										<br >
										<div class="form-group">
											<label>Moneda</label>
											<select name="Moneda" id="Moneda" class="form-control selectpicker" data-live-search="true" validation="not_null" data-nombre="Servicio">
												<option value="">Seleccione...</option>
											</select>
										</div>
										<br>
										<label > Descripción</label>
										<textarea id="Descripcion" name="Descripcion" class="form-control" rows="5"></textarea>
										<br>
									</div>
								</div>
							</div>
						</form>
					</div><!-- /.modal-body -->
					<div class="modal-footer p-b-20 m-b-20">
						<div class="col-sm-12">
							<button type="button" class="btn btn-purple" id="updateServ" name="updateServ">Guardar</button>
						</div>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<script src="../js/jquery-2.2.1.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/nifty.min.js"></script>

		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../plugins/datatables/media/js/jquery.dataTables.js"></script>
		<script src="../plugins/datatables/media/js/dataTables.bootstrap.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../plugins/numbers/jquery.number.js"></script>
		<script src="../plugins/jquery-mask/jquery.mask.min.js"></script>
		<script src="../js/global/methods.js"></script>
		<script src="../js/servicios/verServicios.js"></script>
	</body>
</html>

