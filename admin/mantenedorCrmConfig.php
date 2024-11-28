<?php 
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include('../class/email/opciones.php'); 
include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,sis,manMailFo"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}

$objetoSession->creaMM_restrictGoTo();
$usuario        = $_SESSION['MM_Username'];
$validar        = $_SESSION['MM_UserGroup'];
$nombreUsuario  = $_SESSION['nombreUsuario'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>
    <!--STYLESHEET-->
    <!--=================================================-->
    
    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../css/global/global.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <style>
        body {
            padding-right: 0 !important;
        }
        .cantSMS{
            border: none;
            border-color: transparent;
            text-align: center;
        }
        .apiSMS{
            padding-top: 5px;
            font-size: 13px;
        }
    </style>
    <!--=================================================-->
    <link rel="stylesheet" href="../css/global/global.css">
</head>
<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
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
                    <h1 class="page-header text-overflow">Mantenedor Configuración Crm</h1>
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
					<li><a href="#">Configuración</a></li>
                    <li><a href="#">Sistema</a></li>
                    <li class="active"><a href="#">Mantenedor Configuración Crm</a></li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-lg-12">
					        <div class="panel">                                
                                <div class="panel-heading bg-primary">
                                    <div class="panel-control ">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#demo-tabs-box-1" data-toggle="tab">Configuración Crm</a></li>
                                            <li><a href="#demo-tabs-box-2" data-toggle="tab">Reglas Generales Crm</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="demo-tabs-box-1">
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-1">Código Crm</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="codigoFoco" name="codigoFoco"/>  
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-1">Tipo de Sistema</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="tipoSistema" name="tipoSistema"/>  
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-1">Tipo Menú</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="tipoMenu" name="tipoMenu"/>  
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-1">IP Servidor Discador</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="ipServidor" name="ipServidor"/>  
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-1">Sonido en Notificaciones</label>
                                                    <div class="col-sm-3">
                                                        <input id='sonidoNotificacionesSwitch' name='sonidoNotificacionesSwitch' class='toggle-switch' type='checkbox'>
                                                        <label class='toggle-switch-label'></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-sm-1  pull-right" style="padding-top: 5px;">
                                                <div class="form-group">
                                                    <label class="control-label"></label>
                                                    <input type="submit" value="Guardar" id="sav"
                                                            class="btn btn-primary btn-block guardar"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 pull-right" style="padding-top: 5px;">
                                                <div class="form-group">
                                                    <label class="control-label"></label>
                                                    <input type="submit" value="Actualizar" id="act"
                                                            class="btn btn-success btn-block guardar"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="demo-tabs-box-2">
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Cantidad Máxima Mandantes</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="mandantes" name="mandantes"/>  
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Cantidad Máxima Cedentes</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="cedentes" name="cedentes"/>  
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Nota Máxima Evaluación</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="evaluacion" name="evaluacion"/>  
                                                    </div>
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Cantidad de Correos por hora</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="correos" name="correos"/>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-sm-1  pull-right" style="padding-top: 5px;">
                                                <div class="form-group">
                                                    <label class="control-label"></label>
                                                    <input type="submit" value="Guardar" id="sav2"
                                                            class="btn btn-primary btn-block guardar"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 pull-right" style="padding-top: 5px;">
                                                <div class="form-group">
                                                    <label class="control-label"></label>
                                                    <input type="submit" value="Actualizar" id="act"
                                                            class="btn btn-success btn-block guardar"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                </div>
                <!--End page content-->
            </div>
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
        <footer id="footer">
            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                You have <a href="#" class="text-bold text-main"><span class="label label-danger">3</span> pending action.</a>
            </div>
            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="hide-fixed pull-right pad-rgt">
                <!-- 14GB of <strong>512GB</strong> Free. -->
            </div>
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- <p class="pad-lft">&#0169; 2016 Your Company</p> -->
        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <!--=================================================-->
    <!--jQuery [ REQUIRED ]-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js/bootstrap.min.js"></script>
    <!--Fast Click [ OPTIONAL ]-->
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <!--Nifty Admin [ RECOMMENDED ]-->
    <script src="../js/nifty.min.js"></script>
    <!--Switchery [ OPTIONAL ]-->
    <script src="../plugins/switchery/switchery.min.js"></script>
    <!--Bootstrap Select [ OPTIONAL ]-->
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <!-- Consultas -->
    <script src="../js/admin/configuracionFoco.js"></script>
</body>
</html>