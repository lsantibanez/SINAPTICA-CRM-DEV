<!--NAVBAR-->
<!--===================================================-->
<?php
$logo = $_SESSION['logo']; 
$nombreLogo = $_SESSION['nombreLogo'];
$cedente = (isset($_SESSION['cedente']) && !empty($_SESSION['cedente']))? $_SESSION['cedente']:0;
?>
<header>
	<nav class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="/dashboard/dashboard" title="Ir al inicio">
					<img alt="Brand" src="/img/logo.png">
				</a>
			</div>
			<div class="navbar-content clearfix">
				<ul class="nav navbar-top-links pull-right">
					<?php
						$navBar = new Omni();
						$navBar->navBar();
						if (file_exists('../perfil/img-profile/'.$_SESSION['id_usuario'].'.jpg')) {
							$img = '<img id="img-profile" class="img-circle img-user media-object"  src="../perfil/img-profile/'.$_SESSION['id_usuario'].'.jpg?='.rand().'" class="img-lg img-circle" alt="Profile Picture">';
						} else {
							if ($_SESSION['sexo_usuario'] === 'F') {
								$img = '<img id="img-profile" class="img-circle img-user media-object" src="../img/av6.png" class="img-lg img-circle" alt="Img">';
							} else {
								$img = '<img id="img-profile" class="img-circle img-user media-object" src="../img/av1.png" class="img-lg img-circle" alt="Img">';
							}
						}
					?>
					<li id="dropdown-user" class="dropdown" title="Usuario">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
							<span class="pull-right">
								<?php echo $img; ?>
							</span>
							<div class="username hidden-xs"><?php echo $_SESSION['nombreUsuario']; ?></div>
						</a>
						<div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">
							<ul class="head-list">
								<li>
									<a href="../perfil/index" style="color: #777">
										<i class="fa fa-user"></i>&nbsp;&nbsp;Perfil
									</a>
								</li>
							</ul>
							<div class="pad-all text-right">
								<a href="../index.php?doLogout=true" class="btn btn-block btn-primary" title="Cerrar sesión">
									<i class="fa fa-sign-out"></i>&nbsp;&nbsp;Salir
								</a>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header><!-- Header -->

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
<div class="modal fade" tabindex="-1" role="dialog" id="Cargando2">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="spinner loading"></div>
					<h3 class="text-center">Reorganizando Estrategia </h4>
					<h10 class="text-center">Proceso puede tardar hasta 1 minuto</h10>

				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
if($cedente != 100){ ?>
<script id="InboundCallModalTemplate" type="text/template">
		<div class="row" id="InboundCallModal">
            <div class="panel">
                <div class="panel-heading bg-primary">
                    <h3 class="panel-title">Busqueda por Rut</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Rut:</label>
                                <input type="text" class="form-control" name="rutInbound" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button class="btn btn-primary" name="buscarRutInbound">Buscar</button>
                        </div>
                    </div>
                    
                </div>
            </div>
			<div class="panel">
                <div class="panel-heading bg-primary">
                    <h3 class="panel-title">Registrar Persona</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Rut:</label>
                                <input type="text" class="form-control" name="RutInsertInbound" id="RutInsertInbound"/>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Nombre:</label>
                                <input type="text" class="form-control" name="NombreInsertInbound" id="NombreInsertInbound"/>
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Telefono:</label>
                                <input type="text" class="form-control" name="TelefonoInsertInbound" id="TelefonoInsertInbound"/>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Correo:</label>
                                <input type="text" class="form-control" name="CorreoInsertInbound" id="CorreoInsertInbound"/>
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Dirección:</label>
                                <textarea id="DireccionInsertInbound" name="DireccionInsertInbound" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Comuna:</label>
                                <input type="text" class="form-control" name="ComunaInsertInbound" id="ComunaInsertInbound"/>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Ciudad:</label>
                                <input type="text" class="form-control" name="CiudadInsertInbound" id="CiudadInsertInbound"/>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
		</div>
</script>
<?php } else { ?>
	<script id="InboundCallModalTemplate" type="text/template">
		<div class="row" id="InboundCallModal">
			<div class="panel">
                <div class="panel-heading bg-primary">
                    <h3 class="panel-title">Llamada Entrante</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Origen:</label>
								<select class="select1" id="origenEntrante">
									<option value="Tripulacion">Tripulación</option>
									<option value="Supervision">Supervisión</option>
								</select>
                            </div>
                        </div>
						<div class="col-sm-6">
							<div class="destino_ocultar">
								<label class="control-label">Destino:</label>
								<select class="selectpicker" id="" disabled="disabled" name="" data-live-search="true" data-width="100%">
									<option value="">Seleccione</option>
								</select>
							</div>
							<div class="destino_mostrar">
							    <select class="selectpicker" id="" disabled="disabled" name="" data-live-search="true" data-width="100%">
									<option value="">Seleccione</option>
								</select>
							</div>

                        </div>
                    </div>
					<div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente:</label>
                                <select class="select1" id="clienteIn">
									<option value="N/A">N/A</option>
									<option value="Netland Chile S.A.">Netland Chile S.A</option>
								</select>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Tipificación</label>
								<select class="select1" id="tipificacionIn">
									<option value="Exitoso">Exitoso</option>
									<option value="No Exitoso">No Exitoso</option>
									<option value="Clente no se encontraba">Clente no se encontraba</option>
									<option value="Dirección incorrecta">Dirección incorrecta</option>
									<option value="Otros">Otros</option>
								</select>                            
							</div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Obervación:</label>
                                <textarea id="observacionIn" name="observacionIn" class="form-control"></textarea>
								<input type='hidden' id='tipoInbound' value='1'>
							</div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
</script>

<?php }?>
<script src="../js/jquery-2.2.1.min.js"></script>
	<style media="screen">
		.bootbox.modal {
			background-color: transparent !important;
			z-index: 9999 !important;
			background-image: none !important;
		}
	</style>
<!--===================================================-->
<!--END NAVBAR-->
