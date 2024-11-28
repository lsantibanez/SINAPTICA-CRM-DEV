<?php
    include("../class/global/global.php");
    require_once('../class/session/session.php');
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
    
    $objetoSession = new Session($Permisos,false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "adm,gestion,script"));
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
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
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
            <h1 class="page-header text-overflow">Mantenedor de Scripts</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Configuración</a></li>
            <li><a href="#">Configuración CRM</a></li>
            <li class="active">Mantenedor de Scripts</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="panel">
                    <div class="panel-heading bg-primary">
                        <div class="panel-heading bg-primary">
                            <div class="panel-control ">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#script-tab" data-toggle="tab">Script Resumido</a></li>
                                    <li><a href="#script_completo-tab" data-toggle="tab">Script Completo</a></li>
                                    <li><a href="#politica-tab" data-toggle="tab">Políticas</a></li>
                                    <li><a href="#medio_pago-tab" data-toggle="tab">Medios de Pago</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="script-tab">
                                <div class="tab-base ">
                                    <div class="tab-content">
                                        <button class="btn btn-success" id="CrearScript">Crear Script</button>
                                        <br>
                                        <br>
                                        <div class="table-responsive">
                                            <table id="ScriptTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Cedente</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="script_completo-tab">
                                <div class="tab-base ">
                                    <div class="tab-content">
                                        <button class="btn btn-success" id="CrearScriptCompleto">Crear Script</button>
                                        <br>
                                        <br>
                                        <div class="table-responsive">
                                            <table id="ScriptCompletoTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Cedente</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="politica-tab">
                                <div class="tab-base ">
                                    <div class="tab-content">
                                        <button class="btn btn-success" id="CrearPolitica">Crear Política</button>
                                        <br>
                                        <br>
                                        <div class="table-responsive">
                                            <table id="PoliticaTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Cedente</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="medio_pago-tab">
                                <div class="tab-base ">
                                    <div class="tab-content">
                                        <button class="btn btn-success" id="CrearMedioPago">Crear Medio de Pago</button>
                                        <br>
                                        <br>
                                        <div class="table-responsive">
                                            <table id="MedioPagoTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Cedente</th>
                                                        <th>Acciones</th>
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
        <?php include("../layout/footer.php"); ?>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
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
        <script id="ScriptTemplate" type="text/template">
            <input type="hidden" class="form-control" name='idScript' id='idScript'>
            <div class="row">
                <div class="col-sm-12">
			        <div class="form-group">
				        <label class="control-label">Cedente</label>
                        <select class="form-control selectpicker" title="Seleccione" data-live-search="true" id="id_cedente" name="id_cedente">
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Script:</label>
                        <!-- <textarea class="form-control" id="script" name='script' rows="8"></textarea> -->
                        <div id="script"></div>  
                    </div>
                </div>
            </div>
        </script>
        <script id="PoliticaTemplate" type="text/template">
            <input type="hidden" class="form-control" name='id' id='id'>
            <div class="row">
                <div class="col-sm-12">
			        <div class="form-group">
				        <label class="control-label">Cedente</label>
                        <select class="form-control selectpicker" title="Seleccione" data-live-search="true" id="id_cedente" name="id_cedente">
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Políticas:</label>
                        <div id="politica"></div>  
                    </div>
                </div>
            </div>
        </script>
        <script id="MedioPagoTemplate" type="text/template">
            <input type="hidden" class="form-control" name='id' id='id'>
            <div class="row">
                <div class="col-sm-12">
			        <div class="form-group">
				        <label class="control-label">Cedente</label>
                        <select class="form-control selectpicker" title="Seleccione" data-live-search="true" id="id_cedente" name="id_cedente">
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Medios de Pago:</label>
                        <div id="medio_pago"></div>  
                    </div>
                </div>
            </div>
        </script>
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
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
    <script src="../plugins/summernote/summernote.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/admin/conf_script.js"></script>
</body>
</html>