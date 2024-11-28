<!--NAVBAR-->
<!--===================================================-->
<?php
$logo = $_SESSION['logo']; 
$nombreLogo = $_SESSION['nombreLogo'];
?>

<header id="navbar">
	<div id="navbar-container" class="boxed">
		<!--Logo-->
		<div class="navbar-header">
			<a href="../dashboard/dashboard" class="navbar-brand">
				<img src="<?php echo "../img/".$logo.".png"; ?>" alt="CRM Sinaptica" class="brand-icon">
				<div class="brand-title" style="margin-left:5px">
					<span class="brand-text">
						CRM Sinaptica
				</span>
				</div>
			</a>
		</div>
		<!--End Logo-->

		<!--Navbar Dropdown-->
		<!--================================-->
		<div class="navbar-content clearfix">
			<ul class="nav navbar-top-links pull-left">

				<!--Navigation toogle button-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!-- <li class="tgl-menu-btn">
					<a class="mainnav-toggle" href="#">
						<i class="pli-view-list"></i>
					</a>
				</li> -->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End Navigation toogle button-->

				<li class="dropdown" id="dropdown_prioridad_alta">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
						<i class="pli-bell"></i>
						<span class="badge badge-header badge-danger" id="span_prioridad_alta">0</span>
					</a>

					<!--Notification dropdown menu-->
					<div class="dropdown-menu dropdown-menu-md">
						<div class="pad-all bord-btm">
							<p class="text-semibold text-main mar-no" id="p_prioridad_alta">Tienes 0 Notificaciones.</p>
						</div>
						<div class="nano scrollable has-scrollbar" style="height: 265px;">
							<div class="nano-content" tabindex="0" style="right: -19px;">
								<ul class="head-list" id="ul_prioridad_alta">
									<!-- Dropdown list-->
								</ul>
							</div>
							<div class="nano-pane" style="display: block;">
								<div class="nano-slider" style="height: 146px; transform: translate(0px, 0px);">
								</div>
							</div>
						</div>
					</div>
				</li>

				<li class="dropdown" id="dropdown_prioridad_media">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
						<i class="pli-bell"></i>
						<span class="badge badge-header badge-warning" id="span_prioridad_media">0</span>
					</a>

					<!--Notification dropdown menu-->
					<div class="dropdown-menu dropdown-menu-md">
						<div class="pad-all bord-btm">
							<p class="text-semibold text-main mar-no" id="p_prioridad_media">Tienes 0 Notificaciones.</p>
						</div>
						<div class="nano scrollable has-scrollbar" style="height: 265px;">
							<div class="nano-content" tabindex="0" style="right: -19px;">
								<ul class="head-list" id="ul_prioridad_media">
									<!-- Dropdown list-->
								</ul>
							</div>
							<div class="nano-pane" style="display: block;">
								<div class="nano-slider" style="height: 146px; transform: translate(0px, 0px);">
								</div>
							</div>
						</div>
					</div>
				</li>

				<li class="dropdown" id="dropdown_prioridad_baja">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
						<i class="pli-bell"></i>
						<span class="badge badge-header badge-success" id="span_prioridad_baja">0</span>
					</a>

					<!--Notification dropdown menu-->
					<div class="dropdown-menu dropdown-menu-md">
						<div class="pad-all bord-btm">
							<p class="text-semibold text-main mar-no" id="p_prioridad_baja">Tienes 0 Notificaciones.</p>
						</div>
						<div class="nano scrollable has-scrollbar" style="height: 265px;">
							<div class="nano-content" tabindex="0" style="right: -19px;">
								<ul class="head-list" id="ul_prioridad_baja">
									<!-- Dropdown list-->
								</ul>
							</div>
							<div class="nano-pane" style="display: block;">
								<div class="nano-slider" style="height: 146px; transform: translate(0px, 0px);">
								</div>
							</div>
						</div>
					</div>
				</li>

				<li class="dropdown" id="dropdown_alertas_calidad">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
						<i class="fa fa-check-square-o"></i>
						<span class="badge badge-header badge-success" id="span_alerta_calidad">0</span>
					</a>

					<!--Notification dropdown menu-->
					<div class="dropdown-menu dropdown-menu-md">
						<div class="pad-all bord-btm">
							<p class="text-semibold text-main mar-no" id="p_alerta_calidad">Tienes 0 Notificaciones.</p>
						</div>
						<div class="nano scrollable has-scrollbar" style="height: 265px;">
							<div class="nano-content" tabindex="0" style="right: -19px;">
								<ul class="head-list" id="ul_alerta_calidad">
									<!-- Dropdown list-->
								</ul>
							</div>
							<div class="nano-pane" style="display: block;">
								<div class="nano-slider" style="height: 146px; transform: translate(0px, 0px);">
								</div>
							</div>
						</div>
					</div>
				</li>

				<li class="dropdown" id="dropdown_alertas_inbounbd" style="display: none;">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
						<i class="fa fa-volume-control-phone"></i>
						<span class="badge badge-header badge-success" id="span_alerta_inbounbd" style="margin: 1px -3px 0 0;"></span>
					</a>

					<!--Notification dropdown menu-->
					<div class="dropdown-menu dropdown-menu-md">
						<div class="pad-all bord-btm">
							<p class="text-semibold text-main mar-no" id="p_alerta_inbounbd"></p>
						</div>
						<div class="nano scrollable has-scrollbar" style="height: 265px;">
							<div class="nano-content" tabindex="0" style="right: -19px;">
								<ul class="head-list" id="ul_alerta_inbounbd">
									<!-- Dropdown list-->
									<li>
										<i class="btn btn-danger btnHangUpInbound fa fa-phone" style="float: left; margin: 5px 5px;"></i>
                                        <i class="btn btn-danger btnTransferCallInbound fa fa-exchange" style="float: left; margin: 5px 5px;"></i>
										<i class="btn btn-danger btnStopInbound fa fa-stop" style="float: right; margin: 5px 5px;"></i>
										<i class="btn btn-danger btnPauseInbound fa fa-play" style="float: right; margin: 5px 5px;"></i>
									</li>
								</ul>
							</div>
							<div class="nano-pane" style="display: block;">
								<div class="nano-slider" style="height: 146px; transform: translate(0px, 0px);">
								</div>
							</div>
						</div>
					</div>
				</li>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End notifications dropdown-->
			</ul>
			<ul class="nav navbar-top-links pull-right">

				<!--Language selector-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<?php
					$navBar = new Omni();
					$navBar->navBar();
					if (file_exists('../perfil/img-profile/'.$_SESSION['id_usuario'].'.jpg')) {
						$img = '<img id="img-profile" class="img-circle img-user media-object"  src="../perfil/img-profile/'.$_SESSION['id_usuario'].'.jpg?='.rand().'" class="img-lg img-circle" alt="Profile Picture">';
					} else {
						if ($_SESSION['sexo_usuario'] == "F"){
							$img =  '<img id="img-profile" class="img-circle img-user media-object" src="../img/av6.png" class="img-lg img-circle" alt="Profile Picture">';
						}else{
							$img =  '<img id="img-profile" class="img-circle img-user media-object" src="../img/av1.png" class="img-lg img-circle" alt="Profile Picture">';
						}
				}
				?>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End language selector-->

				<!--User dropdown-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<li id="dropdown-user" class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
						<span class="pull-right">
							<?php echo $img; ?>
						</span>
						<div class="username hidden-xs"><?php echo $_SESSION['nombreUsuario']; ?></div>
					</a>
					<div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">
						<!-- Dropdown heading  -->
						<!-- User dropdown menu -->
						<ul class="head-list">
							<li>
								<a href="../perfil/index">
									<i class="pli-male icon-lg icon-fw"></i> Perfil
								</a>
							</li>
							<!-- <li>
								<a href="#">
									<span class="badge badge-danger pull-right">9</span>
									<i class="pli-mail icon-lg icon-fw"></i> Mensajes
								</a>
							</li>
							<li>
								<a href="#">
									<i class="pli-gear icon-lg icon-fw"></i> Configuración
								</a>
							</li> -->
							<!-- <li>
								<a target="_blank" href="#">
									<i class="pli-information icon-lg icon-fw"></i> Ayuda
								</a>
							</li> -->
						</ul>

						<!-- Dropdown footer -->
						<div class="pad-all text-right">
							<a href="../index.php?doLogout=true" class="btn btn-primary">
								<i class="pli-unlock"></i> Salir
							</a>
						</div>
					</div>
				</li>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End user dropdown-->
			</ul>
		</div>
		<!--================================-->
		<!--End Navbar Dropdown-->
	</div>
</header>
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
