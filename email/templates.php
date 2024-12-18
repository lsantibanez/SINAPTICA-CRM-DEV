<?php 
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include('../class/email/opciones.php'); 
include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,omnica,email_temp"));
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
$nombreUsuario = $_SESSION['nombreUsuario']; ?>
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
    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <!--Summernote [ OPTIONAL ]-->
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <!--Custom CSS-->
    <style>
    .panel-template, .template-sms, .template-email{
        display: none;
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
        <?php include('../layout/header.php'); ?>
        <!--===================================================-->
        <!--END NAVBAR-->
        <div class="boxed">
            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Diseño de Templates</h1>
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
                    <li class="active"><a href="#">Diseño de Templates</a></li>
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
                                    <h3 class="panel-title">Crear Template</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-md-1 control-label" style="padding-top: 5px;font-size: 15px;">
                                            Canal: 
                                        </label>
                                        <div class="col-md-4">
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" id="canal" name="canal">
                                                <option value="0">EMAIL</option>
                                                <option value="1">SMS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
					        <div class="panel panel-template">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <span id="title-template"></span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label for="nombre-template">Nombre</label>
                                            </div>
                                            <div class="col-sm-3 asunto-template">
                                                <label for="asunto-template">Asunto</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <input type="text" id="nombre-template" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-3 asunto-template">
                                                <div class="form-group">
                                                    <input type="text" id="asunto-template" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row template-email">
                        <div class="col-lg-8">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Diseñar Template EMAIL</h3>
                                </div>
                                <div class="panel-body">   
                                    <div id="summernote"></div>                                   
                                    <button id="save-temp" class="btn btn-purple" type="button"> Guardar</button>
                                    <button id="clean-temp" class="btn btn-warning" type="button">Limpiar</button>
                                    <button id="update-temp" class="btn btn-success" disabled="disabled" type="button">
                                        <i class="demo-pli-mail-unread icon-lg icon-fw"></i> Actualizar
                                    </button>
                                    <input type="hidden" id="current-template">
                                </div>
                            </div>
					    </div>
                        <div class="col-lg-4">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Templates EMAIL Guardadas</h3>
                                </div>
                                <div class="panel-body">
                                    <form role="form" class="form-horizontal">                                
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <?php
                                                        if($_SESSION['tipoSistema'] == "1"){
                                                    ?>
                                                            <th>Factura</th>
                                                    <?php
                                                        }
                                                    ?>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="templates">
                                                <?php $templates = new opciones;
                                                    echo utf8_encode($templates->templates());
                                                ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row template-sms">
                        <div class="col-lg-8">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Diseñar Template SMS</h3>
                                </div>
                                <div class="panel-body">
                                    <div style="margin-bottom: 10px;">
                                        <textarea class="form-control" rows="5" id="SMS" maxlength="160"></textarea>
                                    </div>
                                    <button id="save-temp-sms" class="btn btn-purple" type="button"> Guardar</button>
                                    <button id="clean-temp-sms" class="btn btn-warning" type="button">Limpiar</button>
                                    <button id="update-temp-sms" class="btn btn-success" disabled="disabled" type="button">
                                        <i class="demo-pli-mail-unread icon-lg icon-fw"></i> Actualizar
                                    </button>
                                    <h5 class="pull-right" id="count_message"></h5>
                                    <input type="hidden" id="current-template-sms">
                                </div>
                            </div>
					    </div>
                        <div class="col-lg-4">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Templates SMS Guardadas</h3>
                                </div>
                                <div class="panel-body">
                                    <form role="form" class="form-horizontal">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="templates-sms">
                                                <?php $templates = new opciones;
                                                    echo utf8_encode($templates->templatesSMS());
                                                ?>
                                            </tbody>
                                        </table>
                                    </form>
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
            <?php include('../layout/main-menu.php'); ?>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
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
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
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
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <!--jQuery [ REQUIRED ]-->
    <script src="../js/jquery-2.2.4.min.js"></script>
    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js/bootstrap.min.js"></script>
    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="../js/nifty.min.js"></script>
    <!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <!--Summernote [ OPTIONAL ]-->
    <script src="../plugins/summernote/summernote.min.js"></script>
    <!--bootstrap-select [ OPTIONAL ]-->
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <!--Summernote [ OPTIONAL ]-->
    <script src="../js/email/summernote-ini.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/email/email.js"></script>
</body>
</html>