<?PHP
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "car,addField"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
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
    <input type="hidden" id="cedente" value="<?php echo $cedente; ?>">
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
                    <h1 class="page-header text-overflow">Agregar Campos</h1>
                    <!--Searchbox-->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Carga</a></li>
                    <li class="active">Agregar Campos</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
						<div class="eq-height">
                            <!--Panel with Header-->
                            <!--===================================================-->
							<div class="col-sm-12 eq-box-sm">
                                <div class="row">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h3 class="panel-title">Filtros de Busqueda</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Seleccione Tabla:</label>
                                                        <select class="selectpicker form-control" name="Tabla" title="Seleccione" data-live-search="true" data-width="100%">
                                                            <option value="Persona_tmp">Persona</option>
                                                            <option value="Deuda_tmp">Deuda</option>
                                                            <option value="Direcciones_tmp">Direcciones</option>
                                                            <option value="fono_cob_tmp">fono_cob</option>
                                                            <option value="Mail_tmp">Mail</option>
                                                            <option value="pagos_deudas_tmp">Pagos Deudas</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>
                                                        Nombre del campo
                                                    </label>
                                                    <input type="text" class="form-control" name="Campo">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>
                                                        Tipo de campo
                                                    </label>
                                                    <select name="TipoCampo" class="selectpicker form-control" data-live-search="true">
                                                        <option>Seleccione...</option>
                                                        <option>varchar(50)</option>
                                                        <option>varchar(100)</option>
                                                        <option>varchar(500)</option>
                                                        <option>date</option>
                                                        <option>int</option>
                                                        <option>double</option>
                                                        <option>tinyint</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary" id="GuardarCampo">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h3 class="panel-title">Configuración</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <table id="listaTablas" class="display" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                        <th style="width:90%">Tabla</th>
                                                        <th style="width:10%">Acción</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<!--===================================================-->
								<!--End Panel with Header-->
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


        </div>
        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">
            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                <ul class="footer-list list-inline">
                </li>
                </ul>
            </div>

        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
    </div>
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
    <script id="configurarCampos" type="text/template">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group" id="nomtabla">

                    </div>
                </div>
                <div class="col-sm-6">
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-success btn-block agregarCampo" style="margin-bottom: 20px;">Agregar campos</button>
                </div>
            </div>
            <div class="row">
                <!-- Inicio configurar campos -->
                <div class="table-responsive">
                    <table id="listaCampos" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="width:30%">Campo</th>
                                <th style="width:10%">Tipo dato</th>
                                <th style="width:10%">Orden</th>
                                <th style="width:10%">Logica</th>
                                <th style="width:10%">Cedente</th>
                                <th style="width:5%">Eliminar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- Fin configurar campos -->
            </div>
            <br><br><br><br><br>
        </div>
    </script>
    <script id="listaCamposNoConfigurados" type="text/template">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group" id="nomtabla2"></div>
                </div>                                
            </div>                   
        </div>
        <div class="col-md-12">
        <!--<div class="col-sm-6">
        </div> -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="selectpicker" id="campoBD" name="campoBD" data-live-search="true" data-width="100%">
                                <option value="5">Holaaaaa</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
            </div>
        </div>
    </script>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/funciones.js"></script>
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
    <script src="../plugins/audiojs/audio.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/carga/agregarCampo.js"></script>

</body>
</html>
