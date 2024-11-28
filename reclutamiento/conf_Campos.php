<?php
    require_once('../class/db/DB.php');
    require_once('../class/session/session.php');

    include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
    
    $objetoSession = new Session($Permisos,false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "rec,conf_campo"));
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
            <h1 class="page-header text-overflow">Mantenedor de Campos Reclutamiento</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Reclutamiento</a></li>
            <li class="active">Mantenedor de Campos Reclutamiento</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="panel">
                    <div class="panel-heading bg-primary">
                        <h2 class="panel-title">Lista de Campos</h2>
                    </div>
                    <div class="panel-body">
                        <button class="btn btn-success" id="CrearCampo">Crear Campo</button>
                        <br>
                        <br>
                        <div class="table-responsive">
                            <table id="ListaCampos">
                                <thead>
                                    <tr>
                                        <th>Contenedor</th>
                                        <th>Codigo</th>
                                        <th>Titulo</th>
                                        <th>Valor Ejemplo</th>
                                        <th>Valor por Defecto</th>
                                        <th>Tipo</th>
                                        <th>Dinamico</th>
                                        <th>Mandatorio</th>
                                        <th>Deshabilitado</th>
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
        <script id="CrearCampoTemplate" type="text/template">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Contenedor:</label>
                        <select class="selectpicker form-control" name="Contenedor" title="Seleccione" data-live-search="true" data-width="100%"></select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Codigo:</label>
                        <input type="text" class="form-control" name='Codigo'>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Titulo:</label>
                        <input type="text" class="form-control" name='Titulo'>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Valor Ejemplo:</label>
                        <input type="text" class="form-control" name='ValorEjemplo'>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Valor Predeterminado:</label>
                        <input type="text" class="form-control" name='ValorPredeterminado'>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Tipo:</label>
                        <select class="selectpicker form-control" name="Tipo" title="Seleccione" data-live-search="true" data-width="100%"></select>
                    </div>
                </div>
                <div class="col-sm-12" id="SelectOptionsContainer" style="display: none;">
                    <div class="row">
                        <button class="btn btn-success" id="AgregarOpcion">Agregar Opción</button>
                        <br>
                        <br>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="ListaOpciones" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Prioridad</th>
                                        <th>Opción</th>
                                        <th>Seleccionado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Mandatorio:</label>
                        <select class="selectpicker form-control" name="Mandatorio" title="Seleccione" data-live-search="true" data-width="100%">
                            <option value="1">Si</option>
                            <option value="0" selected >No</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Deshabilitado:</label>
                        <select class="selectpicker form-control" name="Deshabilitado" title="Seleccione" data-live-search="true" data-width="100%">
                            <option value="1">Si</option>
                            <option value="0" selected >No</option>
                        </select>
                    </div>
                </div>
            </div>
        </script>
        <script id="AgregarOpcionTemplate" type="text/template">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Prioridad:</label>
                        <input type="text" class="form-control SoloNumeros" name='Prioridad'>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Opción:</label>
                        <input type="text" class="form-control" name='Opcion'>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Seleccionado:</label>
                        <select class="selectpicker form-control" name="Seleccionado" title="Seleccione" data-live-search="true" data-width="100%">
                            <option value="1">Si</option>
                            <option value="0" selected >No</option>
                        </select>
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
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/reclutamiento/conf_campos.js"></script>
</body>
</html>