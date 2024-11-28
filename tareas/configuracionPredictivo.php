<?php
    require_once('../class/db/DB.php');
    require_once('../class/session/session.php');

    include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
    
    $objetoSession = new Session($Permisos,false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "modSup,confPre"));
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
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <style>
        .btn-repro:hover{
            color: red;
            cursor: pointer;
        }
        .btn-repro.Selected{
            color: red;
        }
        .textTransparent
        {
            width: 50px;
            height: 30px;
            border: none;
            text-align: center;
            background-color:transparent;
            text-align: left;
        }
    </style>
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
            <h1 class="page-header text-overflow">Configuración Predictivo</h1>
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <ol class="breadcrumb">
                <li><a href="#">Asignación</a></li>
                <li class="active">Configuración Predictivo</li>
            </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
            <div id="page-content">
                <div class="row">
                    <div class="panel">
                        <div class="panel-heading">
                            <h2 class="panel-title">Configuración Predictivo</h2>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Seleccione Asignación:</label>
                                        <select class="selectpicker form-control" name="Asignacion" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Cantidad de Canales:</label>
                                        <select class="selectpicker form-control"  name="Canales" title="Seleccione" data-live-search="true" data-width="100%">
                                            <option value = '1'>1</option>
                                            <option value = '2'>2</option>
                                            <option value = '3'>3</option>
                                            <option value = '4'>4</option>
                                            <option value = '5'>5</option>
                                            <option value = '6'>6</option>
                                            <option value = '7'>7</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Telefonos por Rut:</label>
                                        <input type="text" class="form-control SoloNumeros" name="TlfxRut">
                                    </div>
                                </div>
                                <!-- <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Seleccione Tipo Telefono:</label>
                                        <select class="selectpicker form-control" multiple="" name="Tipo_Telefono" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                    </div>
                                </div> -->
                                <div class="col-sm-3" style="display:none">
                                    <div class="form-group">
                                        <label class="control-label">Salida:</label>
                                        <select class="selectpicker form-control"  name="Salida" data-live-search="true" data-width="100%">
                                            <option value = "1">Telefónica</option>
                                            <option value = "2">Telefónica + IVR</option>
                                            <option value = "3">IVR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <button class="btn btn-primary" id="Continuar">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <h2 class="panel-title">Queues Creadas :</h2>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="DiscadorTable"  width="100%">
                                    <thead>
                                        <tr>
                                            <th>Cola</th>
                                            <th>Queue</th>
                                            <th></th>
                                            <th>Ver</th>
                                            <th>Status</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script id="TipoCategoriaTemplate" type="text/template">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="control-label" for="name">Tipo de Categoria</label>
                        <select class="selectpicker form-control" title="Seleccione Tipo de Categoría"  name="TipoCategoria" data-live-search="true" data-width="100%">
                                <option value="Colores">Tipos de Contacto</option>
                                <option value="Prioridad_Fonos">Prioridades</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label" for="name">Categoría</label>
                        <select class="selectpicker form-control" multiple title="Seleccione Categoría"  name="Categorias" id="Categorias" data-live-search="true" data-width="100%" data-actions-box="true"></select>
                    </div>
                </div>
            </script>
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
            <div class="modal fade" tabindex="-1" role="dialog" id="modalCola">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Ver Cola</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table id="ColaTable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Cola</th>
                                            <th>Queue</th>
                                            <th>Canales</th>
                                            <th>Telefonos por Rut</th>
                                            <th>Categorias</th>
                                            <th>Tipo de Categorias</th>
                                            <th>Progreso Ruts</th>
                                            <th>Progreso Fonos</th>
                                            <th>Progreso Reinicio</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/tareas/configuracionPredictivo.js"></script>
</body>
</html>
