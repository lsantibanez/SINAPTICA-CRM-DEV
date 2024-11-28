<?php 
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include('../class/email/opciones.php'); 
include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "gra,graf,email_env"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
    //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
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
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <!--Custom CSS-->
    <style>
    #message{
        position: fixed;
        top:5px;
        left:50%;
        width:90%;
        z-index:99;
        max-width: 600px;
        transform: translateX(-50%);
        -moz-transform: translateX(-50%);
        -webkit-transform: translateX(-50%);
    }
    .select1{
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;
    }
    .select2{
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;
    }
    .text1{
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;
    }
    .text2{
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;
    }
    </style>
    <!--=================================================-->
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
                    <h1 class="page-header text-overflow">Correos Enviados</h1>
                    <!--Searchbox-->
                    <!-- <div class="searchbox">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..">
                            <span class="input-group-btn">
                                <button class="text-muted" type="button"><i class="demo-pli-magnifi-glass"></i></button>
                            </span>
                        </div>
                    </div> -->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Omnicanalidad</a></li>
                    <li><a href="#">EMAIL</a></li>
                    <li class="active"><a href="#">Correos Enviados</a></li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Filtros de Búsqueda</h3>
                                </div>
                                <div class="panel-body bodyHeight">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Seleccione rango de fecha</label>
                                            <div id="date-range">
                                                <div class="input-daterange input-group" id="datepicker">
                                                    <input type="text" class="form-control" name="start" id="start"/>
                                                    <span class="input-group-addon">a</span>
                                                    <input type="text" class="form-control" name="end" id="end" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label"></label>
                                            <button class="btn btn-primary" style="margin-top: 22px;" type="submit" 
                                                        id="FiltrarPorFecha">
                                                Filtrar Fecha
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					    <div class="col-lg-12">
					        <div class="panel">                                
                                <div class="panel-heading">
                                    <h3 class="panel-title">Correos Enviados</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table" id="email_enviados">
                                            <thead>
                                                <tr>
                                                    <th>Asignacion</th>
                                                    <th>Template</th>
                                                    <th>Asunto</th>
                                                    <th>Estatus</th>
                                                    <th>Progreso</th>
                                                    <th>Leídos</th>
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
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
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
<!--Summernote [ OPTIONAL ]-->
    <script src="../plugins/summernote/summernote.min.js"></script>
<!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>
<!--SUMMERNOTE INITIATION-->
    <script src="../js/email/summernote-ini.js"></script><script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
<!-- Consultas -->
    <script src="../js/email/email.js"></script>
    <script src="../js/email/emailEnviados.js"></script>
</body>
</html>
